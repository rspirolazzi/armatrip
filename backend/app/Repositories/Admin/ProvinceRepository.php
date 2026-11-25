<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Province;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProvinceRepository
 * @package App\Repositories\Admin
 * @version March 7, 2019, 7:19 pm -03
 *
 * @method Province findWithoutFail($id, $columns = ['*'])
 * @method Province find($id, $columns = ['*'])
 * @method Province first($columns = ['*'])
*/
class ProvinceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Province::class;
    }
}
