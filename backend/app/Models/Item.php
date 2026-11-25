<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Item
 * @package App\Models
 * @version March 4, 2018, 10:35 pm UTC
 *
 * @property string name
 * @property string image
 * @property integer product_id
 * @property integer category_id
 */
class Item extends Model
{
    use SoftDeletes;

    public $table = 'items';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'sub_category_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'image' => 'string',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'description' => 'max:255',
        'category_id' => 'required'
    ];

    public function toArray(){
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "description"=>$this->description,
            "image"=>$this->image,
            "category"=>$this->subCategory ? $this->subCategory->toArray() :[],
        ];
    }
    public function orderItems(){
        return $this->belongsToMany('App\Models\OrderItem');
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function subCategory(){
        return $this->belongsTo(Category::class, 'sub_category_id','id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_items');
    }
}
