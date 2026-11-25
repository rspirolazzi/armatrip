<?php

namespace App\Repositories\Admin;

use App\Models\Admin\DateRange;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DateRangeRepository
 * @package App\Repositories\Admin
 * @version February 23, 2019, 11:50 am -03
 *
 * @method DateRange findWithoutFail($id, $columns = ['*'])
 * @method DateRange find($id, $columns = ['*'])
 * @method DateRange first($columns = ['*'])
*/
class DateRangeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'start_at',
        'end_at',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DateRange::class;
    }
}
