<?php

namespace App\Repositories\Api;

use App\Models\Admin\Package;
use App\Models\Admin\Province;
use App\Models\Order;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PackageRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
 *
 * @method Package find($id, $columns = ['*'])
 * @method Package first($columns = ['*'])
 */
class PackageRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Package::class;
    }

    public function findWithoutFail($id, $columns = ['*'])
    {
        $package = null;
        try {
            $package = parent::findWithoutFail($id, $columns);
        } catch (Exception $e) {
            return;
        }
        return $package;
    }
}
