<?php

namespace App\Repositories\Admin;

use App\Models\Admin\PackageGroup;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PackageGroupRepository
 * @package App\Repositories\Admin
 * @version August 12, 2019, 6:55 pm -03
 *
 * @method PackageGroup findWithoutFail($id, $columns = ['*'])
 * @method PackageGroup find($id, $columns = ['*'])
 * @method PackageGroup first($columns = ['*'])
*/
class PackageGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code',
        'active',
        'description',
        'bg_color',
        'text_color',
        'styles'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PackageGroup::class;
    }
}
