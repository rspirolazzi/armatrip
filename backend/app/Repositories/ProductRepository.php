<?php

namespace App\Repositories;

use App\Models\Admin\Product;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
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
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }
    public function findByCodeWithoutFail($code, $columns = ['*'])
    {
        try {
            return $this->findByField('code', $code, $columns)->first();
        } catch (Exception $e) {
            return;
        }
    }
}
