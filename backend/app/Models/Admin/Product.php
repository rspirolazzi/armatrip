<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @property string name
 * @property string code
 * @property string description
 * @property string image_primary
 * @property string image_secondary
 * @property string image_tertiary
 * @property string icon
 * @property float price
 * @property integer order
 * @property integer min
 * @property integer max
 * @property string tags
 * @property integer category_id
 * @property Category category
 * @property integer parent_id
 * @property boolean active
 * @property boolean allowed_to_sell
 * @package App\Models
 * @version March 4, 2018, 11:23 pm UTC
 *
 */
class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'code',
        'description',
        'image_primary',
        'image_secondary',
        'image_tertiary',
        'icon',
        'price',
        'order',
        'min',
        'max',
        'tags',
        'category_id',
        'parent_id',
        'active',
        'allowed_to_sell'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'tags' => 'string',
        'image_primary' => 'string',
        'image_secondary' => 'string',
        'image_tertiary' => 'string',
        'icon' => 'string',
        'price' => 'decimal',
        'min' => 'integer',
        'max' => 'integer',
        'category_id' => 'integer',
        'parent_id' => 'integer',
        'active' => 'boolean',
        'allowed_to_sell' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:250',
        'code' => 'required|unique:products,code',
        'price' => 'required',
        'order' => 'required',
        'category_id' => 'required',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ordersItem()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function getPriceExAttribute($value)
    {
        $value = 0;
        try {
            if (isset($this->pivot)) {
                if (!empty($this->pivot->price) && $this->pivot->price != null) {
                    $value = $this->pivot->price;
                }
            }
        } catch (\Exception $ex) {
            $value = 0;
        }
        return $value;
    }

    public function getDefaultAttribute()
    {
        if (isset($this->pivot)) {
            return $this->pivot->default;
        }
    }


    public function toArray()
    {
        return [
            "id" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "description" => $this->description,
            "image" => $this->image,
            "icon" => $this->icon,
            //"priceOriginal" => $this->price,
            "order" => $this->order,
            "price" => $this->priceEx,
            "category" => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'order' => $this->category->order,
                'multiple' => $this->category->multiple,
            ],
            "children" => $this->children,
        ];
    }

    public function getTaxAttribute()
    {
        $tax = 0;
        try {
            if (config('armatrip.tax.product_code', '') === $this->code) {

            }
        } catch (\Exception $ex) {
            $tax = 0;
        }
        return $tax;
    }
}
