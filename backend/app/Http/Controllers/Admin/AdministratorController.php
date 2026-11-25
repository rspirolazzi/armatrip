<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateAdministratorRequest;
use App\Http\Requests\Admin\UpdateAdministratorRequest;
use App\Repositories\Admin\AdminRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Response;

class AdministratorController extends AppBaseController
{
    /** @var  AdminRepository */
    private $adminRepository;

    public function __construct(AdminRepository $adminRepo)
    {
        $this->adminRepository = $adminRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $items_for_page = 25;
        $users = $this->adminRepository->paginate($items_for_page);
        return view('admin.administrators.index')
            ->with('users', $users)
            ;
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.administrators.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateAdministratorRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateAdministratorRequest $request)
    {
        $input = $request->all();
        if ($input['change_password'] == 1) {
            $rules = ['password' => 'required|string|min:6|confirmed',];
            $this->validate($request, $rules);
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }
        /*if ($input['role'] == \App\Admin::$ROLE_STORE) {
            $rules['store'] = 'required';
            $this->validate($request, $rules);
        }*/

        $user = $this->adminRepository->create($input);

        $user->assignRole($input['role']);

        Flash::success('El usuario se guardo con éxito.');

        return redirect(route('admin.administrators.index'));
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
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('No se encontro el usuario');

            return redirect(route('admin.administrators.index'));
        }


        return view('admin.administrators.show')->with([
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
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {

            Flash::error('No se encontro el usuario');

            return redirect(route('admin.administrators.index'));
        }

        return view('admin.administrators.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int $id
     * @param UpdateAdministratorRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateAdministratorRequest $request)
    {
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('No se encontro el usuario');

            return redirect(route('admin.administrators.index'));
        }
        $input = $request->all();
        if (isset($input['change_password']) && $input['change_password'] == 1) {
            $rules = ['password' => 'required|string|min:6|confirmed',];

            $this->validate($request, $rules);
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $user = $this->adminRepository->update($input, $id);
        $user->syncRoles($input['role']);

        Flash::success('El usuario se actualizo con éxito.');
        return redirect(route('admin.administrators.index'));
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
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('No se encontro el usuario');

            return redirect(route('admin.administrators.index'));
        }
        $params = [];
        if ($user->role != '') {
            $params['role'] = $user->role;
        }
        $this->adminRepository->delete($id);

        Flash::success('El usuario se elimino con éxito.');

        return redirect(route('admin.administrators.index', $params));
    }
}
