<?php

namespace App\Models;

use App\Models\Admin\Package;
use Eloquent as Model;

/**
 * Class OrderPackage
 * @package App\Models
 * @version February 28, 2019, 5:46 pm -03
 *
 * @property integer package_id
 * @property integer product_id
 * @property boolean required
 * @property boolean default
 * @property float price
 * @property integer order
 * @property float total
 * @property float tax
 * @property int installment
 */
class OrderPackage extends Model
{
    public $table = 'order_packages';

    public $fillable = [
        'package_id',
        'price',
        'qty',
        'total',
        'order_id',
        'tax',
        'installment',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'package_id' => 'integer',
        'price' => 'decimal',
        'qty' => 'integer',
        'total' => 'decimal',
        'order_id' => 'integer',
        'tax' => 'integer',
        'installment' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'package_id' => 'required',
        'price' => 'required',
        'qty' => 'required',
        'total' => 'required',
        'order_id' => 'required',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getNameAttribute()
    {
        return !empty($this->package) ?$this->package->name:'';
    }

    public function findDateRange(){
        return $this->package->findDateRange(
            $this->order->year,
            $this->order->month,
            $this->order->biweekly
        );
    }
}
