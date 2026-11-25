<?php

namespace App\Models\Admin;

use App\Models\Store;
use App\User;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Invoice
 * @package App\Models\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @property integer user_id
 * @property Store store
 * @property Admin user
 * @property InvoiceItem items
 * @property string status
 * @property Carbon sent_at
 */
class Invoice extends Model
{
    use SoftDeletes;

    static public $STATUS_DRAFT = 'draft';
    static public $STATUS_CONFIRMED = 'confirmed';
    static public $STATUS_RECEIVED = 'received';

    public $table = 'invoices';


    protected $dates = ['deleted_at', 'sent_at'];


    public $fillable = [
        'user_id',
        'store_id',
        'status',
        'file',
        'model_type',
        'comment',
        'total',
        'sent_at',
        'order_number',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'store_id' => 'integer',
        'status' => 'string',
        'model_type' => 'string',
        'comment' => 'string',
        'total' => 'float',
        'sent_at' => 'datetime',
        'order_number' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'store_id' => 'required',
        'status' => 'required',
        'model_type' => 'required',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateOrderNumber($store_id)
    {
        $max_id = self::max('id');
        $max_id = $max_id ? $max_id + 1 : 1;
        $order_number = str_pad($store_id, 4, "0", STR_PAD_LEFT);
        $order_number .= '-';
        $order_number .= str_pad($max_id, 8, "0", STR_PAD_LEFT);
        return $order_number;
    }
}
