<?php

namespace App\Models\Admin;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{

    protected $dates=[
        'created_at'
    ];

    public $fillable = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
