<?php

namespace App\Models\Admin;

use App\Models\Store;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Province
 * @package App\Models\Admin
 * @version March 7, 2019, 7:19 pm -03
 *
 * @property string name
 * @property float tax
 * @property boolean active
 */
class Province extends Model
{
    use SoftDeletes;

    public $table = 'provinces';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'code',
        'tax',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'tax' => 'float',
        'active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'code' => 'required|max:20',
        'tax' => 'required',
        'active' => 'required'
    ];


    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_provinces');
    }

    public function children()
    {
        return $this->hasMany(Province::class, 'parent_id');
    }

}
