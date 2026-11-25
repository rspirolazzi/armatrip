<?php

namespace App;

use App\Models\Order;
use App\Models\School;
use App\Notifications\MyOwnResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Rspirolazzi\Coupons\Traits\Rewardable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

/**
 * Class User
 * @property integer id
 * @property mixed schools
 * @property integer active_order_id
 * @property Order active_order
 * @package App
 */
class User extends Authenticatable implements AuthenticatableUserContract
{

    /**
     * @var string
     */
    use Notifiable, Rewardable, SoftDeletes;
    use HasRoles;

    /**
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:75',
        'phone_code' => 'required|max:3',
        'phone_number' => 'required|max:10',
        'email' => 'required|email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_code',
        'phone_number',
        'birthday',
        'email',
        'password',
        'facebookID',
        'is_verified',
        'verified_source',
        'verified_time',
        'active_order_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verified_source', 'verified_time'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Eloquent model method
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
            ]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "name" => $this->name,
            //"last_name" => $this->last_name,
            "phone_code" => $this->phone_code,
            "phone_number" => $this->phone_number,
            "birthday" => $this->birthday,
            "email" => $this->email,
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyOwnResetPassword($token));
    }

    public static function boot()
    {
        parent::boot();

        //while creating/inserting item into db
        static::deleting(function ($user) {
            Log::debug('deleting');
            if ($user->forceDeleting === true) {
                //$user->comments()->delete();
            }
            Log::debug(' Se elimino el usuario', $user->toArray());
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function active_order()
    {
        return $this->belongsTo(Order::class, 'active_order_id');
    }

}
