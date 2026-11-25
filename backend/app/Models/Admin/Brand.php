<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Optix\Media\HasMedia;

/**
 * Class Brand
 * @package App\Models\Admin
 * @version August 24, 2019, 9:37 pm -03
 *
 * @property string name
 * @property string code
 * @property string description
 * @property integer order
 * @property boolean active
 */
class Brand extends Model
{
    use SoftDeletes;
    use HasMedia;
    public static $GROUP_LOGO = 'logo';
    public static $IMAGE_CONVERSION_THUMBNAIL = 'thumbnail';

    public $table = 'brands';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'code',
        'description',
        'link',
        'order',
        'active'
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
        'description' => 'string',
        'link' => 'string',
        'order' => 'integer',
        'active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:250',
        'code' => 'unique:brands,code|required|max:50',
        'order' => 'required',
        'active' => 'required'
    ];


    public function registerMediaGroups()
    {
        $this->addMediaGroup(self::$GROUP_LOGO)->performConversions(self::$IMAGE_CONVERSION_THUMBNAIL);
    }
}
