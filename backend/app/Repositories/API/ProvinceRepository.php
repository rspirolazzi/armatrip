<?php

namespace App\Repositories\API;

use App\Models\Admin\Province;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProvinceRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
 *
 * @method Province find($id, $columns = ['*'])
 * @method Province first($columns = ['*'])
*/
class ProvinceRepository extends BaseRepository
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
        return Province::class;
    }
}
