<?php

namespace App\Repositories;

use App\Models\Admin\Notification;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class NotificationRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
 *
 * @method Notification findWithoutFail($id, $columns = ['*'])
 * @method Notification find($id, $columns = ['*'])
 * @method Notification first($columns = ['*'])
*/
class NotificationRepository extends BaseRepository
{
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
        return Notification::class;
    }
}
