<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Package;
use App\Models\Admin\PackageDateRange;
use App\Models\Admin\PackageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PackageRepository
 * @package App\Repositories\Admin
 * @version February 28, 2019, 5:23 pm -03
 *
 * @method Package findWithoutFail($id, $columns = ['*'])
 * @method Package find($id, $columns = ['*'])
 * @method Package first($columns = ['*'])
 */
class PackageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'store_id',
        'description' => 'like',
        'tags' => 'like',
        'code' => 'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Package::class;
    }

    public function cloneEntity($package, Request $request)
    {
        $new_package = null;
        DB::beginTransaction();
        try {
            Log::info('Clonando el paquete con id ' . $package->id);
            $new_package = $package->replicate();
            $new_package->code = $request->get('code', $package->code);
            $new_package->name = $request->get('name', $package->name);
            $new_package->price = $request->get('price', $package->price);
            $new_package->store_id = $request->get('store_id', $package->store_id);
            Log::debug('Nuevos valors', $new_package->toArray());
            $new_package->save();
            if (!empty($package->products)) {
                Log::debug('Se clonaran la cantidad de productos ' . sizeof($package->products));
                $package->products->each(function ($value) use ($new_package) {
                    //dd($value->pivot);
                    PackageProduct::create([
                        'package_id' => $new_package->id,
                        'product_id' => $value->id,
                        'required' => $value->pivot->required ? $value->pivot->required : 0,
                        'default' => $value->pivot->default ? $value->pivot->default : 0,
                        'price' => $value->pivot->price ? $value->pivot->price : 0,
                        'order' => $value->pivot->order ? $value->pivot->order : 0,
                    ]);
                });
            }
            if (!empty($package->dateRanges)) {
                Log::debug('Se clonaran la cantidad de fechas ' . sizeof($package->dateRanges));
                $package->dateRanges->each(function ($value) use ($new_package) {
                    //dd($value);
                    PackageDateRange::create([
                        'package_id' => $new_package->id,
                        'name' => $value->name,
                        'year' => $value->year,
                        'month' => $value->month,
                        'biweekly' => $value->biweekly,
                        'tax' => $value->tax,
                    ]);
                });
            }
            DB::commit();
            Log::debug('Fin de clonación');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Ocurrio un error al intentar clonar el paquete con id ' . $package->id, [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
        }
        return $new_package;
    }
}
