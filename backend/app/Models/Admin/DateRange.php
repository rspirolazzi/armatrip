<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DateRange
 * @package App\Models\Admin
 * @version February 23, 2019, 11:50 am -03
 *
 * @property string name
 * @property date start_at
 * @property date end_at
 * @property boolean active
 */
class DateRange extends Model
{
    use SoftDeletes;

    public $table = 'date_ranges';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'start_at',
        'end_at',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'start_at' => 'date',
        'end_at' => 'date',
        'active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'start_at' => 'required',
        'end_at' => 'required',
        'active' => 'required'
    ];

    
}
