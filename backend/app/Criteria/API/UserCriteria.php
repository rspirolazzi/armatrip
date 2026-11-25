<?php

namespace App\Criteria\API;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use JWTAuth;
use JWTAuthException;
/**
 * Class UserCriteriaCriteria.
 *
 * @package namespace App\Criteria\API;
 */
class UserCriteria implements CriteriaInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $token = $this->request->get('token', null);
        $user = JWTAuth::toUser($token);
        $model = $model->where('user_id', $user->id);
        return $model;
    }
}
