<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InvoiceItem
 * @package App\Models\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @property integer product_id
 * @property integer qty
 */
class InvoiceItem extends Model
{
    public $table = 'invoice_items';

    public $timestamps = false;

    public $fillable = [
        'invoice_id',
        'model_type',
        'model_id',
        'qty',
        'price',
        'total',
        'extra_1',
        'extra_2',
        'extra_3',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'invoice_id' => 'integer',
        'model_type' => 'string',
        'model_id' => 'integer',
        'qty' => 'integer',
        'price' => 'float',
        'total' => 'float',
        'extra_1' => 'string',
        'extra_2' => 'string',
        'extra_3' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'invoice_id' => 'required',
        'model_type' => 'required',
        'model_id' => 'required|number',
        'qty' => 'required|max:0|number',
    ];

    public function getModelAttribute()
    {
        $model_name = $this->model_type;
        $model = $model_name::find($this->model_id);
        //dd($model);
        return $model;
    }
}
