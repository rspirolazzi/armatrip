<?php

namespace App\Criteria\API;

use App\Admin;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use JWTAuth;
use JWTAuthException;

/**
 * Class StoreByUserCriteria.
 *
 * @package namespace App\Criteria\API;
 */
class StoreByParamCriteria implements CriteriaInterface
{
    protected $store;

    public function __construct($store_code)
    {
        $this->store = Store::where('code', $store_code)->first();
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->store) {
            $model = $model->where('store_id', $this->store->id);
        } else {
            $model = $model->whereNull('store_id');
        }
        return $model;
    }
}