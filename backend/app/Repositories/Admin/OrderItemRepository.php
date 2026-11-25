<?php

namespace App\Repositories\Admin;

use App\Models\OrderItem;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class OrderItemRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:53 pm UTC
 *
 * @method OrderItem findWithoutFail($id, $columns = ['*'])
 * @method OrderItem find($id, $columns = ['*'])
 * @method OrderItem first($columns = ['*'])
*/
class OrderItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'price',
        'qty',
        'total',
        'order_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OrderItem::class;
    }
}
