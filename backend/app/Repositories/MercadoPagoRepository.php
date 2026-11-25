<?php

namespace App\Repositories;

use App\Models\MercadoPago;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MercadoPagoRepository
 * @package App\Repositories
 * @version May 20, 2018, 1:53 pm UTC
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
        'user_id',
        'address_id',
        'address_flat',
        'items',
        'payment',
        'comments',
        'cash',
        'sub_total',
        'delivery',
        'total',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MercadoPago::class;
    }
}
