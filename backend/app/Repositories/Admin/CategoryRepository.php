<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Category;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:25 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
        'description'=>'like',
        'code'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Category::class;
    }
}
