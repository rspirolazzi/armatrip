<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class School
 * @package App\Models\Admin
 * @version September 16, 2019, 3:18 pm -03
 *
 * @property string code
 * @property string name
 * @property string sector
 * @property string sub_sector
 * @property string province
 * @property string department
 * @property string city
 * @property string city_code
 * @property string postal_code
 * @property string phone_area
 */
class School extends Model
{
    use SoftDeletes;

    public $table = 'schools';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'code',
        'name',
        'sector',
        'sub_sector',
        'province',
        'department',
        'city',
        'city_code',
        'postal_code',
        'phone_area',
        'phone_number',
        'address',
        'email',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'sector' => 'string',
        'sub_sector' => 'string',
        'province' => 'string',
        'department' => 'string',
        'city' => 'string',
        'city_code' => 'string',
        'postal_code' => 'string',
        'phone_area' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required',
        'name' => 'required',
    ];
}
