<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/**
 * Class Category
 * @package App\Models
 * @version March 4, 2018, 10:25 pm UTC
 *
 * @property string name
 */
class Category extends Model
{
    use SoftDeletes;

    public $table = 'categories';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description',
        'code',
        'order',
        'multiple',
        'active',
        'allowed_to_sell',
        'required',
        'parent_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'parent_id' => 'integer',
        'order' => 'integer',
        'multiple' => 'boolean',
        'active' => 'boolean',
        'allowed_to_sell' => 'boolean',
        'required' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'code' => 'required|unique:categories,code',
        'order' => 'required',
    ];

    public function toArray()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            'multiple' => $this->multiple,
            'active' => $this->multiple,
            'required' => $this->required,
            "parent_id" => $this->parent_id,
            "order" => $this->order,
        ];
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * @return array
     */
    public function getCasts(): array
    {
        return $this->casts;
    }

    /*public static function getParents()
    {
        $categories = self::whereNull('parent_id')->orderBy('name')->get()->pluck('name', 'id');
        return $categories;
    }*/

    /*public static function boot() {
        parent::boot();

        static::deleting(function($category) {
            Log::debug('Se intentara eliminar la categoria', $category->toArray());
            if($category->forceDeleting === true) {
                $category->comments()->delete();
            }
            Log::debug(' Se elimino la categoría');
        });
    }*/
    public static function getParents($filter = null)
    {
        $categories = self::getOptions($filter);
        return $categories->sortBy('name')->pluck('name', 'id');
    }


    public static function getOptions($filter = null)
    {
        $categories = [];
        if (!is_null($filter)) {
            $categories = self::where($filter)->get();
        } else {
            $categories = self::all();
        }
        function fnGetParentName($category, $categories)
        {
            $parent = $categories->firstWhere('id', $category['parent_id']);
            /*if ($parent['parent_id']) {
                $parent['name'] = fnGetParentName($parent,$categories)."->{$parent['name']}";
            }*/
            return $parent['name'];
        }

        ;
        $categories = $categories->map(function ($category) use ($categories) {
            if ($category['parent_id']) {
                $category['name'] = fnGetParentName($category, $categories) . " -> {$category['name']}";
            }
            return $category;
        });
        return $categories;
    }

    public function getFullNameAttribute()
    {
        $name = '';
        if ($this->parent_id !== null) {
            $name = $this->_transformFullName($this->parent);
        }
        return $name . $this->name;
    }

    protected function _transformFullName($category)
    {
        $name = $category->name . ' -> ';
        if ($category->parent_id !== null) {
            $name .= $this->_transformFullName($category->parent);
        }
        return $name;
    }
}