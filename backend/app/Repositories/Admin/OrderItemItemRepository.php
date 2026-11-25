<?php

namespace App\Repositories\Admin;

use App\Models\OrderItemItem;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class OrderItemItemRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:55 pm UTC
 *
 * @method OrderItemItem findWithoutFail($id, $columns = ['*'])
 * @method OrderItemItem find($id, $columns = ['*'])
 * @method OrderItemItem first($columns = ['*'])
*/
class OrderItemItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'item_id',
        'order_item_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OrderItemItem::class;
    }
}
