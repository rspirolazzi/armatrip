<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact
 * @package App\Models\Admin
 * @version March 14, 2018, 6:02 pm UTC
 *
 * @property string name
 * @property string email
 * @property string comment
 */
class Contact extends Model
{
    use SoftDeletes;

    public $table = 'contacts';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'phone',
        'comment',
        'store_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'phone' => 'string',
        'comment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'phone' => 'required',
        'comment' => 'required',
        'store_code' => 'required',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
    
}
