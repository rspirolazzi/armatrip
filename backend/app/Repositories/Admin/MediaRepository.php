<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Package;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\Admin\IDG\Media;

/**
 * Class PackageRepository
 * @package App\Repositories\Admin
 * @version February 28, 2019, 5:23 pm -03
 *
 * @method Package findWithoutFail($id, $columns = ['*'])
 * @method Package find($id, $columns = ['*'])
 * @method Package first($columns = ['*'])
 */
class MediaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Media::class;
    }
}
