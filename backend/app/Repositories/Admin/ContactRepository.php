<?php

namespace App\Repositories\Admin;

use App\Models\Contact;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ContactRepository
 * @package App\Repositories\Admin
 * @version March 14, 2018, 6:02 pm UTC
 *
 * @method Contact findWithoutFail($id, $columns = ['*'])
 * @method Contact find($id, $columns = ['*'])
 * @method Contact first($columns = ['*'])
*/
class ContactRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
        'phone'=>'like',
        'comment'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Contact::class;
    }
}
