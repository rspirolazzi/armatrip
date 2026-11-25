<?php

namespace App\Repositories;

use App\Models\Store;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class StoreRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
 *
 * @method Store findWithoutFail($id, $columns = ['*'])
 * @method Store find($id, $columns = ['*'])
 * @method Store first($columns = ['*'])
*/
class StoreRepository extends BaseRepository
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
        return Store::class;
    }
}
