<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItemItem
 * @package App\Models
 * @version March 4, 2018, 10:55 pm UTC
 *
 * @property integer item_id
 * @property integer order_item_id
 */
class OrderItemItem extends Model
{
    use SoftDeletes;

    public $table = 'order_item_items';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'item_id',
        'order_item_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'item_id' => 'integer',
        'order_item_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'item_id' => 'required',
        'order_item_id' => 'required'
    ];

    
}
