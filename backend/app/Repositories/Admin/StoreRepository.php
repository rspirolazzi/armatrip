<?php

namespace App\Repositories\Admin;

use App\Models\Store;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class StoreRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:57 pm UTC
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
        'name' => 'like',
        'code' => 'like',
        'email' => 'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Store::class;
    }
}
