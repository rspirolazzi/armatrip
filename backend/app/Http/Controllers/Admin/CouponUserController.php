<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateCouponUserRequest;
use App\Http\Requests\Admin\UpdateCouponUserRequest;
use App\Repositories\Admin\CouponUserRepository;
use App\Http\Controllers\AppBaseController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CouponUserController extends AppBaseController
{
    /**
     * Display a listing of the CouponUser.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $couponUsers = DB::table(config('coupons.table'))
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                config('coupons.table').'.id as coupon_id',
                config('coupons.table').'.name as coupon_name',
                config('coupons.table').'.reward as coupon_reward',
                config('coupons.relation_table').'.used_at',
                'orders.id as order_id',
                'orders.store_id as order_store_id',
                'orders.discount as order_discount',
                'orders.total as order_total',
                ])
            ->join(config('coupons.relation_table'), config('coupons.relation_table').'.coupon_id', '=',config('coupons.table').'.id')
            ->join('users', 'users.id', '=',config('coupons.relation_table').'.'.config('coupons.related_pivot_key'))
            ->join('orders', 'orders.id', '=',config('coupons.relation_table').'.order_id', 'left')
            ->orderBy(config('coupons.relation_table').'.used_at', 'desc')
        ;
        if($request->get('search', null)){
            $couponUsers->where(function($query) use($request){
                return $query
                    ->orWhere('users.name', 'like', "%{$request->get('search')}%")
                    ->orWhere('users.email', 'like', "%{$request->get('search')}%")
                    ->orWhere(config('coupons.table').'.name', 'like', "%{$request->get('search')}%")
                    ->orWhere(config('coupons.table').'.code', 'like', "%{$request->get('search')}%")
                    ;
            });
        }
        $couponUsers =$couponUsers->paginate(50);

        return view('admin.coupon_users.index')
            ->with('couponUsers', $couponUsers);
    }
    /**
     * Display the specified CouponUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $couponUser = $this->couponUserRepository->findWithoutFail($id);

        if (empty($couponUser)) {
            Flash::error('Coupon User no se encontró');

            return redirect(route('admin.coupon_users.index'));
        }

        return view('admin.coupon_users.show')->with('couponUser', $couponUser);
    }
}
