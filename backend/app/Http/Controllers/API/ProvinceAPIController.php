<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Admin\Province;
use App\Models\Admin\SettingManager;
use App\Models\Store;
use App\Repositories\API\ProvinceRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ProvinceController
 * @package App\Http\Controllers\API
 */
class ProvinceAPIController extends AppBaseController
{
    /** @var  ProvinceRepository */
    private $provinceRepository;

    public function __construct(ProvinceRepository $provinceRepo)
    {
        $this->provinceRepository = $provinceRepo;
    }

    /**
     * Display a listing of the Province.
     * GET|HEAD /provinces
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->provinceRepository->pushCriteria(new RequestCriteria($request));
        $this->provinceRepository->pushCriteria(new LimitOffsetCriteria($request));

        $this->provinceRepository->scopeQuery(function ($query) {
            return $query
                ->where('active', 1);
        });
        $provinces = $this->provinceRepository->all();
        return $this->sendResponse($provinces->toArray());
    }

    /**
     * Display a listing of the Province.
     * GET|HEAD /provinces
     *
     * @param $store_code
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function store($store_code, Request $request)
    {
        $this->provinceRepository->pushCriteria(new RequestCriteria($request));
        $this->provinceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $provinces = [];
        $store = Store::where('code', $store_code)->first();
        if (!$store) {
            $default_country = SettingManager::get('default_country', 'argentina');
            $country = Province::where('code', $default_country)->first();
            $provinces = $country->children->toArray();
        } elseif ($store && sizeof($store->provinces) === 1) {
            $provinces = $store->provinces[0]->children->toArray();
        } elseif ($store && sizeof($store->provinces) > 1) {
            $provinces = $store->provinces->toArray();
        }
        return $this->sendResponse($provinces);
    }
}
