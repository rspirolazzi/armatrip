<?php

namespace App\Criteria\API;

use App\Admin;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
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
class PackageGroupActive implements CriteriaInterface
{
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
        $model = $model->whereHas('package_group', function (Builder $query) {
            $query->where('active', true);
        });
        return $model;
    }
}