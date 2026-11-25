<?php

namespace App\Models;

use App\Models\Admin\Product;
use Eloquent as Model;

/**
 * Class OrderPackage
 * @package App\Models
 * @version February 28, 2019, 5:46 pm -03
 *
 * @property integer product_id
 * @property float price
 * @property integer qty
 * @property float total
 * @property integer order_id
 */
class OrderProduct extends Model
{
    public $table = 'order_products';

    public $fillable = [
        'product_id',
        'price',
        'qty',
        'total',
        'order_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'price' => 'float',
        'qty' => 'integer',
        'total' => 'float',
        'order_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'price' => 'required',
        'qty' => 'required',
        'total' => 'required',
        'order_id' => 'required',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
