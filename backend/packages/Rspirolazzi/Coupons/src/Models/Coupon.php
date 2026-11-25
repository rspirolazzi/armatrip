<?php

namespace Rspirolazzi\Coupons\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Rspirolazzi\Coupons\Exceptions\OutOfDateException;
use Rspirolazzi\Coupons\Exceptions\OutOfTimeException;


/**
 * Class Address
 * @package App\Models
 * @version March 4, 2018, 10:46 pm UTC
 *
 * @property string code
 * @property string name
 * @property string description
 * @property boolean reward
 * @property Carbon expires_at
 * @property Carbon start_at
 * @property Carbon end_at
 * @property string hs_start_at
 * @property string hs_end_at
 * @property boolean is_disposable
 * @property boolean enabled
 *
 * @method static byCode(string $code)
 * @method static pluck(string $string)
 * @method static insert(array $records)
 */
class Coupon extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'code',
        'name',
        'description',
        'reward',
        'expires_at',
        'start_at',
        'end_at',
        'hs_start_at',
        'hs_end_at',
        'is_disposable',
        'enabled',
    ];

    public static $rules = [
        'code' => [
            'required',
            'alpha_num',
            'max:8',
            'unique:coupons,code,id,enabled',
        ],
        'name' => [
            'required',
        ],
        'reward' => [
            'required',
            'between:0,100',
        ],
        'start_at' => [
            'required',
            'date',
        ],
        'end_at' => [
            'required',
            'date',
            'after:start_at',
        ],
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
        'expires_at' => 'datetime',
        'hs_start_at' => 'string',
        'hs_end_at' => 'string',
        'is_disposable' => 'boolean',
        'enabled' => 'boolean',
        'data' => 'array',
        'reward' => 'decimal',
        'start_at' => 'date',
        'end_at' => 'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expires_at'];

    /**
     * Coupon constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(array(
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(config('coupons.default_expiration_days', 30)),
        ), true);
        parent::__construct($attributes);

        $this->table = config('coupons.table', 'coupons');
    }

    /**
     * Get the users who is related coupon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('coupons.user_model'), config('coupons.relation_table'),
            config('coupons.foreign_pivot_key', 'user_id'), config('coupons.related_pivot_key', 'user_id'))
            ->withPivot('used_at');
    }

    /**
     * Query builder to find coupon using code.
     *
     * @param $query
     * @param $code
     *
     * @return mixed
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Query builder to get disposable codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeIsDisposable($query)
    {
        return $query->where('is_disposable', true);
    }

    /**
     * Query builder to get non-disposable codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeIsNotDisposable($query)
    {
        return $query->where('is_disposable', false);
    }

    /**
     * Query builder to get expired promotion codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')->whereDate('expires_at', '<=', Carbon::now());
    }

    /**
     * Query builder to get expired promotion codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeAvailable($query)
    {
        return $query
            ->where('enabled', 1)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            });
    }

    /**
     * Check if code is disposable (ont-time).
     *
     * @return bool
     */
    public function isDisposable()
    {
        return $this->is_disposable;
    }

    /**
     * Check if code is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at ? Carbon::now()->gte($this->expires_at) : false;
    }

    /**
     * Check if code is expired.
     *
     * @return bool
     * @throws OutOfDateException
     * @throws OutOfTimeException
     */
    public function isValid()
    {
        $now = Carbon::now();
        if (!$now->between($this->start_at, $this->end_at)) {
            throw new OutOfDateException();
        }
        if (!empty($this->hs_start_at) || !empty($this->hs_end_at)) {
            $nowStart = Carbon::createFromTimeString($now->format('Y-m-d') . ' ' . $this->hs_start_at);
            $nowEnd = Carbon::createFromTimeString($now->format('Y-m-d') . ' ' . $this->hs_end_at);
            if (!$now->between($nowStart, $nowEnd)) {
                throw new OutOfTimeException();
            }
        }
        return true;
    }

    public static function boot()
    {
        parent::boot();

        $fnModel = function ($model) {
            if (!is_null($model->end_at)) {
                $model->expires_at = $model->end_at;
                if (!is_null($model->hs_end_at)) {
                    $time = explode(':', $model->hs_end_at);
                    $model->expires_at = $model->expires_at->addHour($time[0]);
                    $model->expires_at = $model->expires_at->addMinute($time[1]);
                }
            }
            Log::debug('Coupon creating', $model->toArray());
        };

        self::creating($fnModel);

        self::created(function ($model) {
            // ... code here
            Log::debug('Coupon created', $model->toArray());
        });

        self::updating($fnModel);

        self::updated(function ($model) {
            // ... code here
            Log::debug('Coupon updated', $model->toArray());
        });

        self::deleting(function ($model) {
            // ... code here
            Log::debug('Coupon deleting', $model->toArray());
        });

        self::deleted(function ($model) {
            // ... code here
            Log::debug('Coupon deleted', $model->toArray());
        });
    }

    public function getHsStartAtShortAttribute()
    {
        $hs_start_at = explode(':', $this->hs_start_at);
        return "{$hs_start_at[0]}:{$hs_start_at[1]}";
    }

    public function getHsEndAtShortAttribute()
    {
        $hs_end_at = explode(':', $this->hs_end_at);
        return "{$hs_end_at[0]}:{$hs_end_at[1]}";
    }

}
