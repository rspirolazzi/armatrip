<?php

namespace Rspirolazzi\Coupons\Traits;

use Carbon\Carbon;
use Rspirolazzi\Coupons\Models\Coupon;
use Rspirolazzi\Coupons\Facades\Coupons;
use Rspirolazzi\Coupons\Exceptions\AlreadyUsedException;
use Rspirolazzi\Coupons\Exceptions\InvalidCouponException;

trait Rewardable
{
    /**
     * Get the coupons that are related to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, config('coupons.relation_table'))
            ->withPivot('used_at');
    }

    /**
     * Apply coupon to user and get callback.
     *
     * @param string $code
     * @param $order_id
     * @param null|\Closure $callback
     *
     * @return null|\Rspirolazzi\Coupons\Model\Coupon
     */
    public function applyCode($code, $order_id, $callback = null)
    {
        try {
            if ($coupon = Coupons::check($code)) {
                if ($coupon->users()->wherePivot('user_id', $this->id)->exists()) {
                    //throw new AlreadyUsedException;
                }

                $coupon->users()->attach($this->id, [
                    'coupon_id' => $coupon->id,
                    'order_id' => $order_id,
                    'used_at' => Carbon::now(),
                ]);

                $coupon->load('users');

                if (is_callable($callback)) {
                    $callback($coupon);
                }

                return $coupon;
            }
        } catch (InvalidCouponException $exception) {
            //
        }

        if (is_callable($callback)) {
            $callback(null);
        }

        return null;
    }

    /**
     * Redeem coupon to user and get callback.
     *
     * @param string $code
     * @param $order_id
     * @param null|\Closure $callback
     *
     * @return null|\Rspirolazzi\Coupons\Model\Coupon
     * @throws AlreadyUsedException
     */
    public function redeemCode($code, $order_id, $callback = null)
    {
        return $this->applyCode($code, $order_id, $callback);
    }
}
