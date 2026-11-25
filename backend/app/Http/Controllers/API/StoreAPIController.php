<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateStoreAPIRequest;
use App\Http\Requests\API\UpdateStoreAPIRequest;
use App\Models\Admin\SettingManager;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class StoreController
 * @package App\Http\Controllers\API
 */
class StoreAPIController extends AppBaseController
{
    /** @var  StoreRepository */
    private $storeRepository;

    public function __construct(StoreRepository $storeRepo)
    {
        $this->storeRepository = $storeRepo;
    }

    /**
     * Display a listing of the Store.
     * GET|HEAD /stores
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $turismo_individual = SettingManager::get('store_code_individual', 'turismo_individual');
        $this->storeRepository->pushCriteria(new RequestCriteria($request));
        $this->storeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->storeRepository->scopeQuery(function ($query) use ($turismo_individual) {
            return $query->with('media')
                ->where('active', 1)
                ->where('code', '<>', $turismo_individual);
        });
        $stores = $this->storeRepository->all();
        Log::debug('Empresas a devolver', $stores->toArray());
        return $this->sendResponse($stores->toArray());
    }
}
