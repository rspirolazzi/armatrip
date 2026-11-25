<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Currency
 * @package App\Models\Admin
 * @version July 16, 2019, 5:04 pm -03
 *
 * @property string name
 * @property string code
 * @property string symbol
 */
class Currency extends Model
{
    public $table = 'currencies';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'code',
        'symbol'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'symbol' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'code' => 'required',
        'symbol' => 'required'
    ];

    public function toArray()
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
        ];
    }
}
