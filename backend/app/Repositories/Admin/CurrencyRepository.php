<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Currency;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CurrencyRepository
 * @package App\Repositories\Admin
 * @version July 16, 2019, 5:04 pm -03
 *
 * @method Currency findWithoutFail($id, $columns = ['*'])
 * @method Currency find($id, $columns = ['*'])
 * @method Currency first($columns = ['*'])
*/
class CurrencyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code',
        'symbol'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Currency::class;
    }
}
