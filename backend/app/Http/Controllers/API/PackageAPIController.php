<?php

namespace App\Http\Controllers\API;

use App\Criteria\API\PackageGroupActive;
use App\Criteria\API\StoreByParamCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Admin\Province;
use App\Models\Package;
use App\Repositories\OrderRepository;
use App\Repositories\PackageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use JWTAuth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PackageController
 * @package App\Http\Controllers\API
 */
class PackageAPIController extends AppBaseController
{
    /** @var  PackageRepository */
    private $packageRepository;
    private $orderRepository;

    public function __construct(PackageRepository $packageRepo, OrderRepository $orderRepo)
    {
        $this->packageRepository = $packageRepo;
        $this->orderRepository = $orderRepo;
    }

    protected function getAuthUser(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        return $user;
    }

    /**
     * @param null $province_id
     * @return Province
     */
    protected function getProvince($province_id = null)
    {
        $province = null;
        if ($province_id !== null) {
            $province = Province::find($province_id);
        }
        return $province;
    }

    /**
     * Display a listing of the Package.
     * GET|HEAD /packages
     *
     * @param $store_code
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index($store_code, Request $request)
    {

        $this->packageRepository->pushCriteria(new RequestCriteria($request));
        $this->packageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->packageRepository->pushCriteria(new StoreByParamCriteria($store_code));
        $this->packageRepository->pushCriteria(new PackageGroupActive());
        $this->packageRepository->scopeQuery(function ($query) {
            return
                $query->where('active', true);
        });
        $this->packageRepository->orderBy('order', 'asc');
        $province = $this->getProvince($request->get('province_id', null));
        $packages = $this->packageRepository->allWithProvince($province);
        Log::debug('Paquetes a devolver', $packages->toArray());
        return $this->sendResponse($packages->toArray());
    }

    public function detail(Request $request)
    {
        try{
            $user = $this->getAuthUser($request);
            $order = $user->active_order;

            $package_options = $this->packageRepository->getAllOptionsByOrder($order, $request);
            return $this->sendResponse($package_options);
        }catch(\Exception $ex){
            Log::error('Ocurrio un error al obtener el detalle', [
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'code' => $ex->getCode(),
                'traceAsString' => $ex->getTraceAsString(),
            ]);
            return $this->sendError('Ocurrio un error al obtener el detalle del paquete.');
        }
    }
}