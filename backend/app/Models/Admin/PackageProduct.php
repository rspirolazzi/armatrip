<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PackageProduct
 * @package App\Models\Admin
 * @version February 28, 2019, 5:46 pm -03
 *
 * @property integer package_id
 * @property integer product_id
 * @property boolean required
 * @property boolean default
 * @property float price
 * @property integer order
 */
class PackageProduct extends Pivot
{
    public $table = 'package_products';

    public $fillable = [
        'package_id',
        'product_id',
        'required',
        'default',
        'price',
        'order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'package_id' => 'integer',
        'product_id' => 'integer',
        'required' => 'boolean',
        'default' => 'boolean',
        'price' => 'float',
        'order' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'package_id' => 'required',
        'product_id' => 'required',
        'price' => 'required',
    ];
}
