<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Notification;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class NotificationRepository
 * @package App\Repositories\Admin
 * @version April 6, 2019, 2:35 pm -03
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
        'code' => 'like',
        'title' => 'like',
        'message' => 'like',

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }
}
