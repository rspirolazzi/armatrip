<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Optix\Media\HasMedia;

/**
 * Class PackageGroup
 * @package App\Models\Admin
 * @version August 12, 2019, 6:55 pm -03
 *
 * @property string name
 * @property string code
 * @property boolean active
 * @property string description
 * @property string styles
 */
class PackageGroup extends Model
{
    use SoftDeletes;
    use HasMedia;

    public $table = 'package_groups';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'code',
        'active',
        'description',
        'styles'
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
        'active' => 'boolean',
        'description' => 'string',
        'styles' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:50',
        'code' => 'required|max:20',
        'style_value' => 'required'
    ];

    public function getStylesAttribute($value)
    {
        $styles = !empty($value) ? $value : '{}';
        return json_decode($styles, true);
    }

    public function getStyleValueAttribute()
    {
        $value = '';
        if (isset($this->styles['value'])) {
            $value = $this->styles['value'];
        }
        return $value;
    }

    public function registerMediaGroups()
    {
        $this->addMediaGroup(Package::$GROUP_IMAGE)->performConversions(Package::$IMAGE_CONVERSION_THUMBNAIL);
        $this->addMediaGroup(Package::$GROUP_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_THUMBNAIL);
        //$this->addMediaGroup(Package::$GROUP_IMAGE)->performConversions(Package::$IMAGE_CONVERSION_DESKTOP);
        //$this->addMediaGroup(Package::$GROUP_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_DESKTOP);
        //$this->addMediaGroup(Package::$GROUP_IMAGE)->performConversions(Package::$IMAGE_CONVERSION_MOBILE);
        //$this->addMediaGroup(Package::$GROUP_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_MOBILE);
    }

    public function getBackgroundImage($type)
    {
        $background_image = $this->getFirstMediaUrl($type);

        if (!$background_image && $this->package_group) {
            $background_image = $this->package_group->getFirstMediaUrl($type);
        }
        return $background_image;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'active' => $this->active,
            'code' => $this->code,
            'description' => $this->description,
            'name' => $this->name,
            'order' => $this->order,
            'styles' => $this->styles,
            "media" => [
                'background_image' => $this->getBackgroundImage(Package::$GROUP_IMAGE),
                'background_image_mobile' => $this->getBackgroundImage(Package::$GROUP_MOBILE),
            ]
        ];
    }
}
