<?php

namespace App\Repositories\API;

use Rspirolazzi\Coupons\Models\Coupon;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CouponRepository
 * @package App\Repositories\Admin
 * @version October 26, 2018, 4:45 pm UTC
 *
 * @method Coupon findWithoutFail($id, $columns = ['*'])
 * @method Coupon find($id, $columns = ['*'])
 * @method Coupon first($columns = ['*'])
*/
class CouponRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'name',
        'description',
        'reward',
        'is_disposable'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Coupon::class;
    }
}
