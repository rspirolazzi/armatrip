<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateAddressRequest;
use App\Http\Requests\Admin\UpdateAddressRequest;
use App\Repositories\Admin\AddressRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AddressController extends AppBaseController
{
    /** @var  AddressRepository */
    private $addressRepository;

    public function __construct(AddressRepository $addressRepo)
    {
        $this->addressRepository = $addressRepo;
    }

    /**
     * Display a listing of the Address.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->addressRepository->pushCriteria(new RequestCriteria($request));
        $addresses = $this->addressRepository->paginate(25);

        return view('admin.addresses.index')
            ->with('addresses', $addresses);
    }

    /**
     * Show the form for creating a new Address.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        if(!$request->has('user')){
            return redirect()->back();
        }
        return view('admin.addresses.create')->with('user_id',$request->input('user'));
    }

    /**
     * Store a newly created Address in storage.
     *
     * @param CreateAddressRequest $request
     *
     * @return Response
     */
    public function store(CreateAddressRequest $request)
    {

        $input = $request->all();

        $address = $this->addressRepository->create($input);

        Flash::success('Dirección guardada correctamente');

        return redirect()->route('admin.users.show',$request->input('user_id'));
    }

    /**
     * Display the specified Address.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $address = $this->addressRepository->findWithoutFail($id);

        if (empty($address)) {
            Flash::error('Address no se encontró');

            return redirect(route('admin.addresses.index'));
        }

        return view('admin.addresses.show')->with('address', $address);
    }

    /**
     * Show the form for editing the specified Address.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $address = $this->addressRepository->findWithoutFail($id);

        if (empty($address)) {
            Flash::error('Address no se encontró');

            return redirect(route('admin.addresses.index'));
        }

        return view('admin.addresses.edit')->with('address', $address);
    }

    /**
     * Update the specified Address in storage.
     *
     * @param  int              $id
     * @param UpdateAddressRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAddressRequest $request)
    {
        $address = $this->addressRepository->findWithoutFail($id);

        if (empty($address)) {
            Flash::error('Address no se encontró');

            return redirect(route('admin.addresses.index'));
        }

        $address = $this->addressRepository->update($request->all(), $id);

        Flash::success('Address se actualizó con éxito.');

        return redirect(route('admin.addresses.index'));
    }

    /**
     * Remove the specified Address from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $address = $this->addressRepository->findWithoutFail($id);

        if (empty($address)) {
            Flash::error('Address no se encontró');

            return redirect(route('admin.addresses.index'));
        }

        $this->addressRepository->delete($id);

        Flash::success('Address se eliminó con éxito.');

        return redirect()->route('admin.users.show',$request->input('user'));
    }
}
