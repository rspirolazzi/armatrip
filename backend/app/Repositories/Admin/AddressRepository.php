<?php

namespace App\Repositories\Admin;

use App\Models\Address;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AddressRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:46 pm UTC
 *
 * @method Address findWithoutFail($id, $columns = ['*'])
 * @method Address find($id, $columns = ['*'])
 * @method Address first($columns = ['*'])
*/
class AddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'alias',
        'street',
        'number',
        'line1',
        'line2',
        'state',
        'city',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Address::class;
    }
}
