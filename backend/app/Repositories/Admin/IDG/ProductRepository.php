<?php

namespace App\Repositories\Admin\IDG;

use App\Models\Admin\IDG\Product;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:33 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
*/
class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
        'description'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }
}
