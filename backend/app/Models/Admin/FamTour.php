<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Taggable;
/**
 * Class FamTour
 * @package App\Models\Admin
 * @version March 3, 2020, 9:37 pm -03
 *
 
 */
class FamTour extends Model
{
    use SoftDeletes;
    use Taggable;

    public $table = 'fam_tours';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'message_id',
        'subject',
        'date',
        'from',
        'flags',
        'folder',
        'body',
        'link_google_calendar',
        'category',
        'name',        
        'phone',    
        'student',  
        'school',   
        'city', 
        'last_modification',
        'user_id',
        'moved',
        'calendar',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'message_id'=>'string',
        'subject'=>'string',
        'date'=>'datetime',
        'from'=>'string',
        'flags'=>'string',
        'folder'=>'string',
        'body'=>'string',
        'link_google_calendar'=>'string',
        'category'=>'string',
        'name'=>'string',
        'phone'=>'string',
        'student'=>'string',
        'school'=>'string',
        'city'=>'string',
        'last_modification'=>'datetime',
        'user_id'=>'integer',
        'moved'=>'boolean',
        'calendar'=>'datetime',
    ];
    public function getOriginalBodyAttribute(){
        $body = '';
        if($this->body){
            $value = json_decode($this->body);
            if($value && isset($value->original)){
                $body = $value->original;
            }
        }
        return $body;
    }
}