<?php

namespace App\Models\Admin;

use Carbon\Carbon;
use Eloquent as Model;

/**
 * Class PackageDateRange
 * @property integer id
 * @property string name
 * @property integer year
 * @property integer month
 * @property integer biweekly
 * @property Carbon date_start
 * @property Carbon date_end
 * @property boolean active
 * @package App\Models\Admin
 * @version February 28, 2019, 5:23 pm -03
 *
 */
class PackageDateRange extends Model
{
    public $table = 'package_date_ranges';

    protected $dates = [
        'date_start',
        'date_end',
    ];

    public $fillable = [
        'package_id',
        'name',
        'year',
        'month',
        'biweekly',
        'tax',
        'date_start',
        'date_end',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'package_id' => 'integer',
        'name' => 'string',
        'year' => 'integer',
        'month' => 'integer',
        'biweekly' => 'integer',
        'tax' => 'float',
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'package_id' => 'required',
        'name' => 'required',
        'year' => 'required',
        'month' => 'required',
        'biweekly' => 'required',
        'tax' => 'required',
    ];

    public function toArray()
    {
        $value = [
            "id" => $this->id,
            "name" => $this->name,
            "year" => $this->year,
            "month" => $this->month,
            "biweekly" => $this->biweekly,
            "days" => isset($this->date_end) ? $this->date_end->format('Y-m-d') : null,
            "active" => $this->active,
        ];
        if (isset($this->date_start)) {
            $value['date_start'] = $this->date_start->format('Y-m-d');
        }
        if (isset($this->date_end)) {
            $value['date_end'] = $this->date_end->format('Y-m-d');
        }
        return $value;
    }
}
