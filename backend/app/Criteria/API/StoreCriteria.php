<?php

namespace App\Criteria\API;

use App\Admin;
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
class StoreCriteria implements CriteriaInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
        print_r($this->request->toArray());
        exit;
        $model = $model->where('store_id', $this->request->get('store_id', null));
        return $model;
    }
}
