<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Invoice;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class InternalOrderRepository
 * @package App\Repositories\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @method Invoice findWithoutFail($id, $columns = ['*'])
 * @method Invoice find($id, $columns = ['*'])
 * @method Invoice first($columns = ['*'])
*/
class InternalOrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Invoice::class;
    }
}
