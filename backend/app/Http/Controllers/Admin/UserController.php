<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Repositories\Admin\UserRepository;
use App\Http\Controllers\AppBaseController;
use App\Admin as User;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $items_for_page = 25;
        $this->userRepository->scopeQuery(function ($query) {
            return $query->doesntHave('roles');
        });
        $users = $this->userRepository->paginate($items_for_page);
        return view('admin.users.index')
            ->with('users', $users);

    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        if ($input['change_password'] == 1) {
            $rules = ['password' => 'required|string|min:6|confirmed',];
            $this->validate($request, $rules);
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $user = $this->userRepository->create($input);
        $user->syncRoles([]);

        Flash::success('El usuario se guardo con éxito.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('No se encontro el usuario');

            return redirect(route('admin.users.index'));
        }

        
        return view('admin.users.show')->with([
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {

            Flash::error('No se encontro el usuario');

            return redirect(route('admin.users.index'));
        }

        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('No se encontro el usuario');

            return redirect(route('admin.users.index'));
        }
        $input = $request->all();
        if (isset($input['change_password']) && $input['change_password'] == 1) {
            $rules = ['password' => 'required|string|min:6|confirmed',];
            $this->validate($request, $rules);
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $user = $this->userRepository->update($input, $id);
        $user->syncRoles([]);

        Flash::success('El usuario se actualizo con éxito.');
        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('No se encontro el usuario');

            return redirect(route('admin.users.index'));
        }
        $this->userRepository->delete($id);

        Flash::success('El usuario se elimino con éxito.');

        return redirect(route('admin.users.index'));
    }
}
