<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SettingManager extends Model
{
    public static $CACHE = [];
    public $timestamps = false;
    public static $TYPE_STRING = 'string';
    public static $TYPE_INTEGER = 'integer';
    public static $TYPE_BOOLEAN = 'boolean';
    public static $TYPE_SELECT = 'select';
    public static $TYPE_DECIMAL = 'decimal';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('setting.database.table');
    }

    public $fillable = [
        'label',
        'key',
        'value',
        'description',
        'type',
        'options',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'label' => 'string',
        'description' => 'string',
        'type' => 'string',
        'options' => 'string'
    ];

    public function getOptionsAttribute($value)
    {
        if ($value) {
            return json_decode($value, true);
        }
        return [];
    }

    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        }
    }

    public static function get($key, $default = null)
    {
        $value = $default;
        if (!empty(self::$CACHE[$key])) {
            $value = self::$CACHE[$key];
        } else {
            $setting = self::where('key', $key)->first();
            if ($setting) {
                self::$CACHE[$key] = $setting->value;
                $value = $setting->value;
            }
        }
        return $value;
    }

}
