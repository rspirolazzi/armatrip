<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Product;
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
class ProductItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'image',
        'price',
        'min',
        'max',
        'category_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }
}
