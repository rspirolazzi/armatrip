<?php

namespace App\Models;

use App\Models\Admin\Currency;
use App\Models\Admin\Province;
use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Optix\Media\HasMedia;

/**
 * Class Store
 * @package App\Models
 * @version March 4, 2018, 10:57 pm UTC
 *
 * @property string name
 * @property string address
 * @property string telephone
 * @property string lat
 * @property string lon
 * @property integer id
 * @property string code
 * @property string tags
 * @property string icon
 * @property boolean active
 * @property string email
 */
class Store extends Model
{
    use SoftDeletes;
    use HasMedia;

    public static $GROUP_ICON = 'icon';
    public static $IMAGE_CONVERSION_ICON = 'store_icon';

    public $table = 'stores';

    protected $dates = ['deleted_at'];

    public static $CURRENCY_PESOS = 'common';
    public static $CURRENCY_DOLLARS = 'dollars';


    public $fillable = [
        'name',
        'code',
        'email',
        'telephone',
        'tags',
        'active',
        'is_foreign',
        'currency_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'email' => 'string',
        'telephone' => 'string',
        'tags' => 'string',
        'icon' => 'string',
        'active' => 'boolean',
        'is_foreign' => 'boolean',
        'currency_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'code' => 'unique:stores,code|required|max:20',
        'active' => 'required',
        'is_foreign' => 'required',
        'email' => 'nullable|email',

    ];

    public function admin()
    {
        return $this->hasOne(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function provinces()
    {
        return $this->belongsToMany(Province::class, 'store_provinces');
    }

    public function registerMediaGroups()
    {
        $this->addMediaGroup(self::$GROUP_ICON)->performConversions(self::$IMAGE_CONVERSION_ICON);
    }

    /**
     * URL de la imagen. La columna "icon" conserva las cargadas antes en base64.
     *
     * @return string|null
     */
    public function getIconUrl()
    {
        return $this->getFirstMediaUrl(self::$GROUP_ICON, self::$IMAGE_CONVERSION_ICON) ?: $this->icon;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'tags' => $this->tags,
            'icon' => $this->getIconUrl(),
            'active' => $this->active,
            'is_foreign' => $this->is_foreign,
            'options' => $this->options,
            'currency' => $this->currency,
        ];
    }


    protected function getOptionsAttribute()
    {
        $is_foreign = $this->is_foreign;
        return [
            'currency_message' => $is_foreign ? 'Los valores cotizados son en USD (Dólares)' : '',
        ];
    }

    public function getMPKey()
    {
        return (!$this->mp_enabled_test ? $this->mp_public_key : $this->mp_public_key_test);
    }

    public function getMPAccessToken()
    {
        return (!$this->mp_enabled_test ? $this->mp_access_token : $this->mp_access_token_test);
    }

    public function toArrayAdmin($ignoreFields = [])
    {
        $values = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'tags' => $this->tags,
            'icon' => $this->getIconUrl(),
            'active' => $this->active,
        ];
        foreach ($ignoreFields as $row) {
            if (isset($values[$row])) {
                unset($values[$row]);
            }
        }
        return $values;
    }
}
