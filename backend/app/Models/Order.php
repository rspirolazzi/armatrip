<?php

namespace App\Models;

use App\Models\Admin\Province;
use App\Models\Admin\School;
use App\User;
use Carbon\Carbon;
use Eloquent as Model;
use Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Order
 * @package App\Models
 * @version May 20, 2018, 2:07 pm UTC
 *
 * @property integer user_id
 * @property string comments
 * @property float sub_total
 * @property float discount
 * @property float total
 * @property integer status
 * @property string number
 * @property OrderPackage package
 * @property array products
 * @property User user
 * @property integer id
 * @property integer school_id
 * @property string school_name
 * @property string school_city
 * @property Province province
 * @property string public_code_url
 * @property Carbon reserved_at
 * @property Carbon date_start
 * @property Carbon date_end
 */
class Order extends Model
{
    use SoftDeletes;

    static public $STATUS_PENDING = 'pending';
    static public $STATUS_CANCELLED = 'cancelled';
    static public $STATUS_FINISHED = 'finished';
    static public $STATUS_SOLD = 'sold';
    static public $STATUS_CONTACTED = 'contacted';

    static public $PAYMENT_CASH = 'cash';
    static public $PAYMENT_CARD = 'card';


    public $table = 'orders';


    protected $dates = ['deleted_at', 'reserved_at', 'date_start', 'date_end'];


    public $fillable = [
        'user_id',
        'store_id',
        'province_id',
        'comments',
        'sub_total',
        'discount',
        'total',
        'status',
        'number',
        'year',
        'month',
        'biweekly',
        'school_id',
        'school_name',
        'school_city',
        'public_code_url',
        'reserved_at',
        'date_start',
        'date_end',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'store_id' => 'integer',
        'province_id' => 'integer',
        'comments' => 'string',
        'status' => 'string',
        'year' => 'integer',
        'month' => 'integer',
        'biweekly' => 'integer',
        'public_code_url' => 'string',
        'school_id' => 'integer',
        'school_name' => 'string',
        'school_city' => 'string',
        'date_start'=>'date',
        'date_end'=>'date',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'store_id' => 'required',
        'province_id' => 'required',
        'total' => 'required',
        'year' => 'required',
        'month' => 'required',
        'biweekly' => 'required',
    ];

    public function dateToTravel()
    {
        return Carbon::createFromDate($this->year, $this->month, $this->day);
    }

    public function package()
    {
        return $this->hasOne(OrderPackage::class);
    }
    public function mercadoPagos()
    {
        return $this->hasMany(MercadoPago::class);
    }
    public function getMercadoPagoAttribute()
    {
        return $this->mercadoPagos->last();
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getSchoolNameCompleteAttribute()
    {
        $name = '';
        if ($this->school_id) {
            $name = "{$this->school->name} - {$this->school->city}";
        } else {
            $name = "{$this->school_name} - {$this->school_city}";
        }
        return $name;
    }

    public function generatePaymentGateway()
    {

    }


    public static function getNumber($prefix, $id = 1)
    {
        $prefix_number = str_pad($prefix, 4, '0', STR_PAD_LEFT);
        $order = str_pad($id, 8, '0', STR_PAD_LEFT);
        return "$prefix_number-$order";
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (Order $order) {
            if ($order->package && $order->package->package && $order->package->package->name && $order->number)
                $order->public_code_url = Str::slug("{$order->package->package->name}-{$order->number}");
        });
        static::updating(function (Order $order) {
            if (!$order->public_code_url && ($order->package && $order->package->package && $order->package->package->name && $order->number)) {
                $order->public_code_url = Str::slug("{$order->package->package->name}-{$order->number}");
            }
        });
    }

    public function toArrayAdmin()
    {
        return [
            "id" => $this->id,
            "user" => $this->user->toArray(),
            "store" => $this->store->toArrayAdmin(['icon']),
            "province" => $this->province->toArray(),
            "number" => $this->number,
            "comments" => $this->comments,
            "sub_total" => $this->sub_total,
            "discount" => $this->discount,
            "total" => $this->total,
            "year" => $this->year,
            "month" => $this->month,
            "biweekly" => $this->biweekly,
            "status" => $this->status,
            "public_code_url" => $this->public_code_url,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "reserved_at" => $this->reserved_at,
            "school" => is_null($this->school_id) ? [
                "school_name" => $this->school_name,
                "school_city" => $this->school_city,
            ] : $this->school->toArray(),
        ];
    }
    public function coupons(){
        return $this->belongsToMany(\Rspirolazzi\Coupons\Models\Coupon::class, config('coupons.relation_table'));
    }
    public function getCouponAttribute(){
        return $this->coupons->last();
    }
}