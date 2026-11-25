<?php

use Faker\Factory as Faker;
use App\Models\Admin\Coupon;
use App\Repositories\Admin\CouponRepository;

trait MakeCouponTrait
{
    /**
     * Create fake instance of Coupon and save it in database
     *
     * @param array $couponFields
     * @return Coupon
     */
    public function makeCoupon($couponFields = [])
    {
        /** @var CouponRepository $couponRepo */
        $couponRepo = App::make(CouponRepository::class);
        $theme = $this->fakeCouponData($couponFields);
        return $couponRepo->create($theme);
    }

    /**
     * Get fake instance of Coupon
     *
     * @param array $couponFields
     * @return Coupon
     */
    public function fakeCoupon($couponFields = [])
    {
        return new Coupon($this->fakeCouponData($couponFields));
    }

    /**
     * Get fake data of Coupon
     *
     * @param array $postFields
     * @return array
     */
    public function fakeCouponData($couponFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'code' => $fake->word,
            'name' => $fake->word,
            'description' => $fake->word,
            'reward' => $fake->word,
            'is_disposable' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $couponFields);
    }
}
