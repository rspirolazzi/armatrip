<?php

namespace App\Repositories\Admin;

use App\Models\Admin\PackageProduct;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PackageProductRepository
 * @package App\Repositories\Admin
 * @version February 28, 2019, 5:46 pm -03
 *
 * @method PackageProduct findWithoutFail($id, $columns = ['*'])
 * @method PackageProduct find($id, $columns = ['*'])
 * @method PackageProduct first($columns = ['*'])
*/
class PackageProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'package_id',
        'product_id',
        'default',
        'price'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PackageProduct::class;
    }
}
