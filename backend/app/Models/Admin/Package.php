<?php

namespace App\Models\Admin;

use App\Models\Store;
use App\Models\Traits\HasVideo;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Optix\Media\HasMedia;


/**
 * Class Package
 * @package App\Models\Admin
 * @version February 28, 2019, 5:23 pm -03
 *
 * @property string name
 * @property boolean active
 * @property boolean shareable
 * @property string description
 * @property float price
 * @property string image
 * @property integer order
 * @property string tags
 * @property string code
 * @property string video_link
 * @property float increment_percent
 * @property integer package_group_id
 * @property PackageGroup package_group
 * @property float tax
 */
class Package extends Model
{
    use SoftDeletes;
    use HasMedia;
    use HasVideo;

    public static $GROUP_IMAGE = 'image';
    public static $GROUP_MOBILE = 'image_mobile';
    public static $IMAGE_CONVERSION_THUMBNAIL = 'thumbnail';
    public static $IMAGE_CONVERSION_DESKTOP = 'desktop';
    public static $IMAGE_CONVERSION_MOBILE = 'mobile';
    public static $IMAGE_CONVERSION_MULTIMEDIA_DESKTOP = 'multimedia_desktop';
    public static $IMAGE_CONVERSION_MULTIMEDIA_MOBILE = 'multimedia_mobile';
    public static $GROUP_MULTIMEDIA = 'multimedia';
    public static $GROUP_MULTIMEDIA_MOBILE = 'multimedia_mobile';

    public $table = 'packages';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'active',
        'description',
        'price',
        'increment_percent',
        'image',
        'order',
        'tags',
        'code',
        'store_id',
        'tax',
        'installment',
        'package_group_id',
        'video_link',
        'shareable',
        'more_info',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'shareable' => 'boolean',
        'active' => 'boolean',
        'description' => 'string',
        'price' => 'float',
        'increment_percent' => 'float',
        'image' => 'string',
        'order' => 'integer',
        'tags' => 'string',
        'code' => 'string',
        'store_id' => 'integer',
        'tax' => 'float',
        'installment' => 'integer',
        'package_group_id' => 'integer',
        'video_link' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'active' => 'required',
        'shareable' => 'required',
        'price' => 'required',
        'increment_percent' => 'required',
        'installment' => 'required|numeric|min:1',
        'tax' => 'required',
        'code' => 'required|unique:packages,code',
        'store_id' => 'required',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_products')
            ->using(PackageProduct::class, 'package_products')
            ->withPivot([
                'id',
                'price',
                'default',
                'order',
            ]);
    }

    public function package_group()
    {
        return $this->belongsTo(PackageGroup::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function dateRanges()
    {
        return $this->hasMany(PackageDateRange::class);
    }

    public function getFinalPriceAttribute()
    {
        $value = $this->price;
        if ($this->increment_percent && $this->increment_percent > 0) {
            $value = \App\Helpers\Helper::applyTax($value, $this->increment_percent);
        }
        return $value;
    }

    public function findDateRange($year, $month, $biweekly)
    {
        if ($this->dateRanges->count() > 0) {

            $dateRangesResult = $this->dateRanges->first(function ($dateRange) use ($year, $month, $biweekly) {
                return (
                    $dateRange['year'] == $year &&
                    (
                        ($dateRange['month'] == $month || $dateRange['month'] == '') &&
                        ($dateRange['biweekly'] == $biweekly || $dateRange['biweekly'] == '')
                    )
                );
            });
            return $dateRangesResult;
        }
        return null;
    }

    public function getBackgroundImage($type)
    {
        $background_image = $this->getFirstMediaUrl($type);

        if (!$background_image) {
            $background_image = $this->package_group->getFirstMediaUrl($type);
        }
        return $background_image;
    }

    public function getMultimediaVideos()
    {
        //dd($this->videos);
        return $this->videos->map(function($video){
            return [
                //'id'=>$video->id,
                //'embed'=>$video->embed,
                'video_html'=>$video->video_html,
            ];
        });
    }

    public function getMultimediaImages()
    {
        $package = $this;
        $images = $this->getMedia(self::$GROUP_MULTIMEDIA)->map(function ($media) use ($package) {
            return [
                'title' => $package->name,
                'description' => $media->name,
                'image' => $media->getUrl(self::$IMAGE_CONVERSION_MULTIMEDIA_DESKTOP),
            ];
        })->values()->toArray();
        $mobile_images = $this->getMedia(self::$GROUP_MULTIMEDIA_MOBILE)->map(function ($media) use ($package) {
            return [
                'title' => $package->name,
                'description' => $media->name,
                'image' => $media->getUrl(self::$IMAGE_CONVERSION_MULTIMEDIA_MOBILE),
            ];
        })->values()->toArray();
        return ["" . self::$GROUP_MULTIMEDIA . "" => $images, "" . self::$GROUP_MULTIMEDIA_MOBILE . "" => $mobile_images];
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "store_id" => $this->store_id,
            "active" => $this->active,
            "description" => $this->description,
            "image" => $this->image,
            "order" => $this->order,
            "tags" => $this->tags,
            "code" => $this->code,
            "tax" => $this->tax,
            "installment" => $this->installment,
            "dateRanges" => $this->dateRanges,
            "package_group" => $this->package_group,
            "more_info" => $this->more_info,
            "photos" => $this->getMultimediaImages(),
            "videos" => $this->getMultimediaVideos(),
            "media" => [
                'background_image' => $this->getBackgroundImage(self::$GROUP_IMAGE),
                'background_image_mobile' => $this->getBackgroundImage(self::$GROUP_MOBILE),
                'video_link' => $this->video_link,
            ]
        ];
    }

    public function registerMediaGroups()
    {
        $this->addMediaGroup(Package::$GROUP_IMAGE)->performConversions(Package::$IMAGE_CONVERSION_THUMBNAIL);
        $this->addMediaGroup(Package::$GROUP_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_THUMBNAIL);
        //Imagenes para la sección multimedia de los paquetes
        $this->addMediaGroup(Package::$GROUP_MULTIMEDIA)->performConversions(Package::$IMAGE_CONVERSION_MULTIMEDIA_DESKTOP);
        $this->addMediaGroup(Package::$GROUP_MULTIMEDIA_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_MULTIMEDIA_MOBILE);
        //$this->addMediaGroup(Package::$GROUP_IMAGE)->performConversions(Package::$IMAGE_CONVERSION_DESKTOP);
        //$this->addMediaGroup(Package::$GROUP_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_DESKTOP);
        //$this->addMediaGroup(Package::$GROUP_IMAGE)->performConversions(Package::$IMAGE_CONVERSION_MOBILE);
        //$this->addMediaGroup(Package::$GROUP_MOBILE)->performConversions(Package::$IMAGE_CONVERSION_MOBILE);
    }
}