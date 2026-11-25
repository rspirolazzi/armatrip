<?php

namespace App\Repositories\Admin;

use App\Models\Item;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ItemRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:35 pm UTC
 *
 * @method Item findWithoutFail($id, $columns = ['*'])
 * @method Item find($id, $columns = ['*'])
 * @method Item first($columns = ['*'])
*/
class ItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'product_id',
        'category_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Item::class;
    }
}
