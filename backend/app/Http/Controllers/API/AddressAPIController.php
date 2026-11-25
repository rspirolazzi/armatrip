<?php

namespace App\Http\Controllers\API;

use App\Criteria\API\UserCriteria;
use App\Models\Address;
use App\Repositories\Admin\AddressRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response, Validator, JWTAuth;

/**
 * Class AddressController
 * @package App\Http\Controllers\API
 */
class AddressAPIController extends AppBaseController
{
    /** @var  AddressRepository */
    private $addressRepository;

    protected function getUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
    }

    public function __construct(AddressRepository $addressRepo)
    {
        $this->addressRepository = $addressRepo;
    }

    /**
     * Display a listing of the Address.
     * GET|HEAD /addresses
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->addressRepository->pushCriteria(new RequestCriteria($request));
        $this->addressRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->addressRepository->pushCriteria(new UserCriteria($request));
        $this->addressRepository->orderBy('created_at','desc');
        $addresses = $this->addressRepository->all();

        return $this->sendResponse($addresses->toArray());
    }

    /**
     * Store a newly created Address in storage.
     * POST /addresses
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user = $this->getUser();
        $input = $request->all();
        $input['user_id'] = $user->id;
        $rules = [
            'alias' => 'required|max:255',
            'street' => 'required|max:255',
            'number' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'user_id' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $addresses = $this->addressRepository->create($input);
        return $this->sendResponse($addresses->toArray(), 'Address se guardó con éxito');
    }

    /**
     * Update the specified Address in storage.
     * PUT/PATCH /addresses/{id}
     *
     * @param  int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $user = $this->getUser();
        $input = $request->all();

        /** @var Address $address */
        $address = $this->addressRepository->findWithoutFail($id);

        if (empty($address) || $address->user_id != $user->id) {
            return $this->sendError('Address no se encontró');
        }

        $address = $this->addressRepository->update($input, $id);

        return $this->sendResponse($address->toArray(), 'Address se actualizó con éxito');
    }

    /**
     * Remove the specified Address from storage.
     * DELETE /addresses/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->getUser();
        /** @var Address $address */
        $address = $this->addressRepository->findWithoutFail($id);

        if (empty($address) || $address->user_id != $user->id) {
            return $this->sendError('Address no se encontró');
        }

        $address->delete();

        return $this->sendResponse([], 'Address se eliminó con éxito');
    }
}
