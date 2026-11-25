<?php

namespace App\Repositories\Admin;

use App\Models\Admin\InvoiceItem;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class InternalOrderItemRepository
 * @package App\Repositories\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @method InvoiceItem findWithoutFail($id, $columns = ['*'])
 * @method InvoiceItem find($id, $columns = ['*'])
 * @method InvoiceItem first($columns = ['*'])
*/
class InternalOrderItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'qty'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return InvoiceItem::class;
    }
}
