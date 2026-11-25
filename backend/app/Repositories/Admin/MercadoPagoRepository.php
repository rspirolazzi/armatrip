<?php

namespace App\Repositories\Admin;

use App\Models\MercadoPago;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MercadoPagoRepository
 * @package App\Repositories\Admin
 * @version May 5, 2018, 5:11 pm UTC
 *
 * @method MercadoPago findWithoutFail($id, $columns = ['*'])
 * @method MercadoPago find($id, $columns = ['*'])
 * @method MercadoPago first($columns = ['*'])
*/
class MercadoPagoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MercadoPago::class;
    }
}
