<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItem
 * @package App\Models
 * @version March 4, 2018, 10:53 pm UTC
 *
 * @property integer product_id
 * @property decimal price
 * @property integer qty
 * @property decimal total
 * @property integer order_id
 */
class OrderItem extends Model
{
    use SoftDeletes;

    public $table = 'order_items';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'product_id',
        'price',
        'qty',
        'total',
        'order_id',
        'items',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'qty' => 'integer',
        'order_id' => 'integer',
        'items' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'price' => 'required',
        'total' => 'required',
        'order_id' => 'required',
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\Item');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function toArray()
    {
        return [
            "name" => !is_null($this->product) ? $this->product->name : '',
            "price" => $this->price,
            "qty" => $this->qty,
            "total" => $this->total,
            "items" => $this->items,
        ];
    }
}
