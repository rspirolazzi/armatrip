<?php

namespace Rspirolazzi\Coupons;

use Carbon\Carbon;
use Rspirolazzi\Coupons\Models\Coupon;
use Rspirolazzi\Coupons\Exceptions\AlreadyUsedException;
use Rspirolazzi\Coupons\Exceptions\UnauthenticatedException;
use Rspirolazzi\Coupons\Exceptions\InvalidCouponException;

class Coupons
{
    /**
     * Generated codes will be saved here
     * to be validated later.
     *
     * @var array
     */
    private $codes = [];

    /**
     * Length of code will be calculated from asterisks you have
     * set as mask in your config file.
     *
     * @var int
     */
    private $length;

    /**
     * Coupons constructor.
     */
    public function __construct()
    {
        $this->codes = Coupon::pluck('code')->toArray();
        $this->length = substr_count(config('coupons.mask'), '*');
    }

    /**
     * Generates coupons as many as you wish.
     *
     * @param int $amount
     *
     * @return array
     */
    public function output($amount = 1)
    {
        $collection = [];

        for ($i = 1; $i <= $amount; $i++) {
            $random = $this->generate();

            while (!$this->validate($collection, $random)) {
                $random = $this->generate();
            }

            array_push($collection, $random);
        }

        return $collection;
    }

    /**
     * Save coupons into database
     * Successful insert returns generated coupons
     * Fail will return empty collection.
     *
     * @param int $amount
     * @param null $reward
     * @param array $data
     * @param int|null $expires_in
     * @param bool $is_disposable
     *
     * @return \Illuminate\Support\Collection
     */
    public function create($amount = 1, $reward = null, array $data = [], $expires_in = null, $is_disposable = false)
    {
        $records = [];

        foreach ($this->output($amount) as $code) {
            $records[] = [
                'code' => $code,
                'reward' => $reward,
                'data' => json_encode($data),
                'expires_at' => $expires_in ? Carbon::now()->addDays($expires_in) : null,
                'is_disposable' => $is_disposable,
            ];
        }

        if (Coupon::insert($records)) {
            return collect($records)->map(function ($record) {
                $record['data'] = json_decode($record['data'], true);

                return $record;
            });
        }

        return collect([]);
    }

    /**
     * Save one-time use coupons into database
     * Successful insert returns generated coupons
     * Fail will return empty collection.
     *
     * @param int $amount
     * @param null $reward
     * @param array $data
     * @param int|null $expires_in
     *
     * @return \Illuminate\Support\Collection
     */
    public function createDisposable($amount = 1, $reward = null, array $data = [], $expires_in = null)
    {
        return $this->create($amount, $reward, $data, $expires_in, true);
    }

    /**
     * Check coupon in database if it is valid.
     *
     * @param string $code
     *
     * @return bool|Coupon
     * @throws InvalidCouponException
     */
    public function check($code)
    {
        $coupon = Coupon::byCode($code)->first();
        if ($coupon === null) {
            throw new InvalidCouponException;
        }
        $coupon->isValid();
        if ($coupon->enabled === false  || $coupon->isExpired() || ($coupon->isDisposable() && $coupon->users()->exists())) {
            return false;
        }
        return $coupon;
    }
    /**
     * Get Promotion code
     *
     * @param string $code
     *
     * @return bool|Coupon
     * @throws InvalidCouponException
     */
    public function byCode($code)
    {
        $coupon = Coupon::byCode($code)->first();
        if ($coupon === null) {
            throw new InvalidCouponException;
        }
        return $coupon;
    }

    /**
     * Apply coupon to user that it's used from now.
     *
     * @param string $code
     *
     * @param null $store_id
     * @param null $order_id
     * @return bool|Coupon
     * @throws InvalidCouponException
     * @throws UnauthenticatedException
     */
    public function apply($code, $order_id=null)
    {
        if (!auth()->check()) {
            throw new UnauthenticatedException;
        }

        try {
            if ($coupon = $this->check($code)) {
                /*if ($this->isSecondUsageAttempt($coupon)) {
                    throw new AlreadyUsedException;
                }*/

                $coupon->users()->attach(auth()->id(), [
                    'coupon_id' => $coupon->id,
                    'order_id' => $order_id,
                    'used_at' => Carbon::now(),
                ]);

                return $coupon->load('users');
            }
        } catch (InvalidCouponException $exception) {
            throw  $exception;
        }

        return false;
    }

    /**
     * Reedem coupon to user that it's used from now.
     *
     * @param string $code
     *
     * @return bool|Coupon
     * @throws AlreadyUsedException
     * @throws UnauthenticatedException
     */
    public function redeem($code, $store_id=null,$order_id=null)
    {
        return $this->apply($code, $store_id,$order_id);
    }

    /**
     * Expire code as it won't usable anymore.
     *
     * @param string $code
     * @return bool
     * @throws InvalidCouponException
     */
    public function disable($code)
    {
        $coupon = Coupon::byCode($code)->first();

        if ($coupon === null) {
            throw new InvalidCouponException;
        }

        $coupon->expires_at = Carbon::now();

        return $coupon->save();
    }

    /**
     * Clear all expired and used promotion codes
     * that can not be used anymore.
     *
     * @return void
     */
    public function clearRedundant()
    {
        Coupon::all()->each(function (Coupon $coupon) {
            if ($coupon->isExpired() || ($coupon->isDisposable() && $coupon->users()->exists())) {
                $coupon->users()->detach();
                $coupon->delete();
            }
        });
    }

    /**
     * Here will be generated single code using your parameters from config.
     *
     * @return string
     */
    private function generate()
    {
        $characters = config('coupons.characters');
        $mask = config('coupons.mask');
        $coupon = '';
        $random = [];

        for ($i = 1; $i <= $this->length; $i++) {
            $character = $characters[rand(0, strlen($characters) - 1)];
            $random[] = $character;
        }

        shuffle($random);
        $length = count($random);

        $coupon .= $this->getPrefix();

        for ($i = 0; $i < $length; $i++) {
            $mask = preg_replace('/\*/', $random[$i], $mask, 1);
        }

        $coupon .= $mask;
        $coupon .= $this->getSuffix();

        return $coupon;
    }

    /**
     * Generate prefix with separator for coupon.
     *
     * @return string
     */
    private function getPrefix()
    {
        return (bool)config('coupons.prefix')
            ? config('coupons.prefix') . config('coupons.separator')
            : '';
    }

    /**
     * Generate suffix with separator for coupon.
     *
     * @return string
     */
    private function getSuffix()
    {
        return (bool)config('coupons.suffix')
            ? config('coupons.separator') . config('coupons.suffix')
            : '';
    }

    /**
     * Your code will be validated to be unique for one request.
     *
     * @param $collection
     * @param $new
     *
     * @return bool
     */
    private function validate($collection, $new)
    {
        return !in_array($new, array_merge($collection, $this->codes));
    }

    /**
     * Check if user is trying to apply code again.
     *
     * @param Coupon $coupon
     *
     * @return bool
     */
    public function isSecondUsageAttempt(Coupon $coupon)
    {
        return $coupon->users()->wherePivot('user_id', auth()->id())->exists();
    }
}
