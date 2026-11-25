<?php

namespace App\Repositories\Admin;

use App\Models\Admin\School;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SchoolRepository
 * @package App\Repositories\Admin
 * @version September 16, 2019, 3:18 pm -03
 *
 * @method School findWithoutFail($id, $columns = ['*'])
 * @method School find($id, $columns = ['*'])
 * @method School first($columns = ['*'])
*/
class SchoolRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code'=>'like',
        'name'=>'like',
        'province'=>'like',
        'city'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return School::class;
    }
}
