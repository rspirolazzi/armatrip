<?php

namespace App\Repositories\Admin;

use App\Models\Admin\FamTour;
use InfyOm\Generator\Common\BaseRepository;
use Webklex\IMAP\Facades\Client;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:46 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
 */
class FamTourRepository extends BaseRepository
{

    public static $MAP_FIELDS =[
        'name'=>[
            'Nombre del Padre',
            'De',
        ],
        'phone'=>[
            'Tel del Padre',
            'Tel',
        ],
        'mail'=>[
            'Mail',
        ],
        'student'=>[
            'Nombre de alumno',
        ],
        'school'=>[
            'Colegio',
            'Escuela',
        ],
        'city'=>[
            'Localidad',
        ],    
    ];

    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FamTour::class;
    }

    public function syncEmails($limit=null){
        $result = $this->getEmailsPendings($limit);
        foreach ($result as $value) {
            $this->createFamTour($value);
        }
        return $result;
    }

    public function getEmailsPendings($limit = null){
        $result = collect();
        $limit = $limit ? $limit : config('imap.search.limit', 50);
        try{
            $oClient = Client::account('default');
            //Connect to the IMAP Server
            $oClient->connect();
            //Get all Mailboxes
            /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
            $inboxFolder = $oClient->getFolder(config('imap.search.folder'));
            $result = $inboxFolder
                ->query()
                ->subject(config('imap.search.subject'))
                ->limit($limit)
                ->get();
            Log::debug('Se obtuvieron los emails', [
                'folder' => config('imap.search.folder'),
                'subject' => config('imap.search.subject'),
                'count' => $result->count(),
            ]);
        }catch(Exception $e){
            Log::error('Ocurrio un error inesperado al intentar obtener los emails', [
                'folder' => config('imap.search.folder'),
                'subject' => config('imap.search.subject'),
                'error' => $e->__toString(),
            ]);
        }
        return $result;
    }
    public function parseBody($body){
        $values = [];
        $values = [
            'original'=>$body,
        ];
        foreach(explode("\r\n", $body) as $value){
            $row = explode(':', $value);
            $k = trim($row[0]);
            $v = isset($row[1]) ? trim($row[1]) : null;
            if($v && $k){
                $key = $this->getKeyByLabelFromBody($k);
                $key = $key ? $key : $k;
                $values[$key] = [
                    'label'=>$key,
                    'value'=>$v,
                ];
                Log::debug('getKeyByLabelFromBody => Original:'. $k .' ==> Resultado:'.$key, $values);
            }
        }
        return $values;
    }

    protected function getKeyByLabelFromBody($value_to_find){
        $value = collect(self::$MAP_FIELDS)->search(function ($item, $key) use($value_to_find) {
            $find = collect($item)->search(function ($i, $k) use($value_to_find) {
                return strcmp(strtolower($value_to_find),strtolower($i)) === 0;
            });
            return $find !== false;
        });
        return $value;
    }

    protected function getValuesFromBody($body, $key, $default=null){
        $value = $default;
        if(isset($body[$key]) && isset($body[$key]['value'])){
            $value = $body[$key]['value'];
        }
        return $value;
    }

    public function createFamTour(\Webklex\IMAP\Message $message){
        $famTour = null;
        try{
            $_body = $message->getBodies();
            $body = '';
            if($_body['text'] && $_body['text']->content){
                $body = $this->parseBody($_body['text']->content);
            }           
                        
            $attributes = [
                'message_id'=>$message->getMessageId(),
                'subject'=>$message->getSubject(),
                'date'=>$message->getDate(),
                'from'=>$this->getValuesFromBody($body, 'mail'),
                'flags'=>'',/*$message->getFlags()->filter(function($flag){
                    return $flag === 1;
                })->map(function($value, $flag){
                    return $flag;
                }),*/
                'folder'=>$message->getFolder()->name,
                'body'=> json_encode($body),
                'name'=>$this->getValuesFromBody($body,'name'),       
                'phone'=>$this->getValuesFromBody($body,'phone'),
                'student'=>$this->getValuesFromBody($body,'student'),
                'school'=>$this->getValuesFromBody($body,'school'),
                'city'=>$this->getValuesFromBody($body,'city'),
            ];
            $famTour = $this->findByField('message_id', $message->getMessageId())->first();
            if($famTour){
                $this->update($attributes, $famTour->id);
                Log::info('El mensaje con id ' . $message->getMessageId() . ' se actualizó en la base de datos');
            }else{
                $famTour = $this->create($attributes);
                Log::info('El mensaje con id ' . $message->getMessageId() . ' se creó en la base de datos');
            }
            if(config('imap.after_save_message_move', false)){
                Log::debug('Se procedera a mover el mensaje id ' . $message->getMessageId());
                if($this->applyToMessageSaved($message)){
                    $famTour->moved = true;
                    $famTour->save();
                }
            }            
        }catch(Exception $e){
            Log::error('Ocurrio un error inesperado al intentar crear el mensaje ' . $message->getMessageId(), [
                'error' => $e->__toString(),
            ]);
        }        
        return $famTour;
    }
    public function applyToMessageSaved(\Webklex\IMAP\Message $message){
        $value = false;
        try{
            $value = $message->moveToFolder(config('imap.search.folder_to_move'));
            if($value){
                Log::info('El mensaje con id ' . $message->getMessageId() . ' se movio con éxito a ' . config('imap.search.folder_to_move'));
            }else{
                Log::error('El mensaje con id ' . $message->getMessageId() . ' no se pudo mover a ' . config('imap.search.folder_to_move'));
            }
        }catch(Exception $e){
            Log::error('Ocurrio un error inesperado al intentar mover el mensaje ' . $message->getMessageId(), [
                'error' => $e->__toString(),
            ]);
        }
        return $value;
    }
    public function tag($famTour, $category){
        try{
            $famTour->retag(array_filter(array_map('trim', explode(',', $category))));
            Log::debug('La categoría/s "' . $category . '" se aplico con éxito en fam_tour id' . $famTour->id);
        }catch(Exception $e){
            Log::error('Ocurrio un error inesperado al intentar taggear el fam tour id:' . $famTour->id, [
                'error' => $e->__toString(),
            ]);
            throw $e;
        }
        return $famTour;
    }
}