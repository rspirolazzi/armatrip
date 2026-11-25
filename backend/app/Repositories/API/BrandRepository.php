<?php

namespace App\Repositories\API;

use App\Models\Admin\Brand;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class BrandRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
 *
 * @method Brand find($id, $columns = ['*'])
 * @method Brand first($columns = ['*'])
*/
class BrandRepository extends BaseRepository
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
        return Brand::class;
    }
}
