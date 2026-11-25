<?php

namespace App\Repositories;

use App\Models\OrderItem;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class OrderItemRepository
 * @package App\Repositories
 * @version March 4, 2018, 10:15 pm UTC
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
        'order_id',
        'price',
        'qty',
        'total'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OrderItem::class;
    }
}
