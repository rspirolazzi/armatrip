<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Brand;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class BrandRepository
 * @package App\Repositories\Admin
 * @version August 24, 2019, 9:37 pm -03
 *
 * @method Brand findWithoutFail($id, $columns = ['*'])
 * @method Brand find($id, $columns = ['*'])
 * @method Brand first($columns = ['*'])
*/
class BrandRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code',
        'description',
        'order',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Brand::class;
    }
    public function getMaxOrder(){
        $order = $this->model->max('order');
        $order = $order ? $order + 1 : 1;
        return $order;
    }
}
