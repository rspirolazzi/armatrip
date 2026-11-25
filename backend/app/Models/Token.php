<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public static $_SOURCE_SMS = 'sms';
    public static $_SOURCE_EMAIL = 'email';
    public static $_SOURCE_FACEBOOK= 'facebook';

    protected $table='user_verifications';

    public $fillable = [
        'user_id',
        'token',
        'source',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'token' => 'string',
        'source' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'token' => 'required',
        'source' => 'required',
    ];

    /**
     * Generate a six digits code
     *
     * @param int $codeLength
     * @return string
     */
    public static function generateCodeSMS($codeLength = 4)
    {
        $min = pow(10, $codeLength);
        $max = $min * 10 - 1;
        $code = random_int($min, $max);

        return $code;
    }

    /**
     * Generate a 30 digits code
     *
     * @param int $codeLength
     * @return string
     */
    public static function generateCodeEmail($codeLength = 30)
    {
        $verification_code = str_random($codeLength); //Generate verification code
        return $verification_code;
    }

    /**
     * User tokens relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
