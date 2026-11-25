<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Notification
 * @package App\Models\Admin
 * @version April 6, 2019, 2:35 pm -03
 *
 * @property string code
 * @property boolean active
 * @property string title
 * @property string message
 * @property integer duration
 * @property enum type
 * @property integer order
 * @property string uri
 * @property string rules
 * @property bool is_custom
 */
class Notification extends Model
{
    use SoftDeletes;

    public $table = 'notifications';


    protected $dates = ['deleted_at'];

    public static $TYPE_SUCCESS = 'success';
    public static $TYPE_WARNING = 'warning';
    public static $TYPE_DANGER = 'danger';
    public static $TYPE_INFO = 'info';

    public static function getTypes()
    {
        return [
            self::$TYPE_INFO,
            self::$TYPE_SUCCESS,
            self::$TYPE_WARNING,
            self::$TYPE_DANGER,
        ];
    }

    public static function getTypeOptions()
    {
        $options = [];
        collect(self::getTypes())->each(function ($type) use (&$options) {
            $options["$type"] = __("messages.$type");
        });
        return $options;
    }

    public $fillable = [
        'code',
        'active',
        'title',
        'message',
        'duration',
        'type',
        'order',
        'uri',
        'rules',
        'active',
        'is_custom',
        'is_actions',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
        'active' => 'boolean',
        'is_custom' => 'boolean',
        'is_actions' => 'boolean',
        'title' => 'string',
        'message' => 'string',
        'duration' => 'integer',
        'type' => 'string',
        'order' => 'integer',
        'uri' => 'string',
        'rules' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => ['required'],
        'title' => 'required',
        'message' => 'required',
        'duration' => 'numeric',
        'type' => 'required',
        'order' => 'numeric',
        'uri' => 'required'
    ];

    public function toArray()
    {
        return [
            "active" => $this->active,
            "code" => $this->code,
            "duration" => $this->duration,
            "id" => $this->id,
            "message" => $this->message,
            "order" => $this->order,
            "title" => $this->title,
            "type" => $this->type,
            "uri" => $this->uri,
            "is_custom" => $this->is_custom,
            "is_actions" => $this->is_actions,
            "rules" => $this->is_actions && $this->rules ? json_decode($this->rules) : null,
        ];
    }
}
