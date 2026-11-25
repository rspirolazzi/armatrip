<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidUploadException;
use App\Helpers\ResponseUtil;
use App\Http\Controllers\Traits\AttachMedia;
use App\Http\Requests\Admin\CreatePackageRequest;
use App\Http\Requests\Admin\UpdatePackageProductRequest;
use App\Http\Requests\Admin\UpdatePackageRequest;
use App\Models\Admin\Package;
use App\Models\Admin\PackageDateRange;
use App\Models\Admin\PackageProduct;
use App\Models\Admin\Product;
use App\Models\Admin\SettingManager;
use App\Models\Video;
use App\Repositories\Admin\PackageRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Api\PackageRepository as PackageSimulationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PackageController extends AppBaseController
{
    use AttachMedia;
    /** @var  PackageRepository */
    private $packageRepository;
    private $packageSimulationRepository;

    public function __construct(PackageRepository $packageRepo, PackageSimulationRepository $packageSimulationRepository)
    {
        $this->packageRepository = $packageRepo;
        $this->packageSimulationRepository = $packageSimulationRepository;
    }

    /**
     * Display a listing of the Package.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->packageRepository->pushCriteria(new RequestCriteria($request));
        $packages = $this->packageRepository->paginate(25);

        return view('admin.packages.index')
            ->with('packages', $packages);
    }

    /**
     * Show the form for creating a new Package.
     *
     * @return Response
     */
    public function create()
    {
        $order = Package::max('order');
        $order = $order ? $order + 1 : 1;
        return view('admin.packages.create')
            ->with('order', $order);
    }

    /**
     * Store a newly created Package in storage.
     *
     * @param CreatePackageRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreatePackageRequest $request)
    {
        $input = $request->all();


        $package = $this->packageRepository->create($input);
        $this->uploadImage($request, 'background_image', $package, Package::$GROUP_IMAGE);
        $this->uploadImage($request, 'background_image_mobile', $package, Package::$GROUP_MOBILE);

        Flash::success('El paquete se guardó con éxito.');

        return redirect(route('admin.packages.index'));
    }

    /**
     * Display the specified Package.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');

            return redirect(route('admin.packages.index'));
        }

        return view('admin.packages.show')->with('package', $package);
    }

    /**
     * Display the specified Package.
     *
     * @param Request $request
     * @return Response
     */
    public function clone(Request $request)
    {
        $id = $request->get('id');

        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');
            return redirect(route('admin.packages.index'));
        }
        $package_clone = $this->packageRepository->cloneEntity($package, $request);
        if ($package_clone) {
            Flash::success('El paquete se copio con éxito');
            return redirect(route('admin.packages.show', [$package_clone->id]));
        } else {
            Flash::error('Ocurrio un error y no se pudo copiar el paquete ' . $package->name);
        }
        return redirect(route('admin.packages.index'));
    }

    /**
     * Show the form for editing the specified Package.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');

            return redirect(route('admin.packages.index'));
        }

        //dd($package->getFirstMedia('image'));

        return view('admin.packages.edit')->with('package', $package);
    }

    /**
     * Update the specified Package in storage.
     *
     * @param int $id
     * @param UpdatePackageRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdatePackageRequest $request)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');

            return redirect(route('admin.packages.index'));
        }
        $input = $request->all();

        $package = $this->packageRepository->update($input, $id);

        $this->uploadImage($request, 'background_image', $package, Package::$GROUP_IMAGE);
        $this->uploadImage($request, 'background_image_mobile', $package, Package::$GROUP_MOBILE);

        Flash::success('El paquete se actualizó con éxito.');

        return redirect(route('admin.packages.index'));
    }

    /**
     * Remove the specified Package from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');

            return redirect(route('admin.packages.index'));
        }

        $this->packageRepository->delete($id);

        Flash::success('El paquete se eliminó con éxito.');

        return redirect(route('admin.packages.index'));
    }

    public function products($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');

            return redirect(route('admin.packages.index'));
        }
        $products = Product::all();

        return view('admin.packages.products')
            ->with('package', $package)
            ->with('products', $products);
    }

    public function updateProducts($id, UpdatePackageProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->packageRepository->findWithoutFail($id);

            Log::debug('Comienzo de actualizacion de paquete', ['package' => $package->toArray(), 'products' => $package->products->toArray()]);
            if (empty($package)) {
                Flash::error('El paquete no se encontró');
                return redirect(route('admin.packages.index'));
            }

            $products_normalized = [];
            foreach ($request->get('product') as $value) {
                $default = isset($value['default']) ? $value['default'] : false;
                $products = collect($value['products'])->map(function ($product) use ($default) {
                    if (!empty($product['product_id'])) {
                        $product['default'] = collect($default)->search($product['product_id'], true) !== false;
                    }
                    return $product;
                })->all();
                $products_normalized = array_merge($products_normalized, $products);
            }

            //dd($request->toArray(), $products_normalized);

            $product_ids = collect($products_normalized)
                ->filter(function ($value) {
                    return isset($value['product_id']);
                })->map(function ($value) {
                    return $value['product_id'];
                });

            $product_update_ids = collect($products_normalized)->filter(function ($value) {
                return !is_null($value['id']);
            });
            $product_create_ids = collect($products_normalized)->filter(function ($value) {
                return is_null($value['id']) && isset($value['product_id']);
            });

            $productsToDelete = $package->products->whereNotIn('id', $product_ids);

            Log::debug('Productos a procesar en el paquete', $product_ids->toArray());
            Log::debug('Productos a borrar en el paquete', $productsToDelete->toArray());
            Log::debug('Productos a actualizar en el paquete', $product_update_ids->toArray());
            Log::debug('Productos a agregar en el paquete', $product_create_ids->toArray());

            foreach ($productsToDelete->all() as $toDelete) {
                Log::debug('Quitando producto', $toDelete->toArray());
                $package->products()
                    ->newPivotStatement()
                    ->where('id', $toDelete->pivot->id)
                    ->delete();
            }

            foreach ($product_update_ids as $productFromPost) {
                $productUpdate = $package->products()
                    ->newPivotStatement()
                    ->where('id', $productFromPost['id']);

                if ($productUpdate) {
                    Log::debug('Actualizando producto', array_merge($productFromPost, ['package_id' => $id]));
                    unset($productFromPost['id']);
                    $productUpdate->update($productFromPost);
                }
            }
            foreach ($product_create_ids as $productFromPost) {
                Log::debug('Agregando producto', array_merge($productFromPost, ['package_id' => $id]));
                PackageProduct::create([
                    'package_id' => $package->id,
                    'product_id' => $productFromPost['product_id'],
                    'required' => false,
                    'default' => $productFromPost['default'],
                    'price' => $productFromPost['price'],
                    'order' => $productFromPost['order'],
                ]);
            }
            Log::debug('Fin de actualizacion de paquete', ['package' => $package->toArray(), 'products' => $package->products->toArray()]);
            Log::info('El paquete se actualizo con éxito', ['id' => $package->id]);
            DB::commit();
            Flash::success('Se actualizo con éxito los productos');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar actualizar los productos', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            Flash::error('Ocurrio un error al intentar actualizar los productos');
            DB::rollback();
            throw $ex;

        }
        return redirect(route('admin.packages.products', [$id]));
    }

    public function dateRange($id)
    {
        $turismo_individual = SettingManager::get('store_code_individual', 'turismo_individual');
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');
            return redirect(route('admin.packages.index'));
        }
        $view = 'admin.packages.biweeklyRange';
        if ($package->store->code == $turismo_individual) {
            $view = 'admin.packages.dateRange';
        }

        return view($view)
            ->with('package', $package);
    }

    public function updateDateRange($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->packageRepository->findWithoutFail($id);

            if (empty($package)) {
                Flash::error('El paquete no se encontró');

                return redirect(route('admin.packages.index'));
            }

            $dateRanges = collect($request->get('dateRange'));
            $delete_ids = $dateRanges->filter(function ($date) {
                return isset($date['delete']);
            })->map(function ($date) {
                return $date['delete'];
            })->all();

            $new_dates = $dateRanges->filter(function ($date, $index) {
                return $index == 0;
            })->first();
            $update_dates = $dateRanges->filter(function ($date, $index) {
                return ($index > 0 && !isset($date['delete']));
            })->all();

            if ($delete_ids) {
                foreach ($delete_ids as $id => $date) {
                    $package->dateRanges()->where('id', $id)->delete();
                }
            }
            if ($new_dates) {
                if(!empty($new_dates['date_start'])){
                    $new_dates['date_start'] = Carbon::createFromFormat('d/m/yy', $new_dates['date_start']);
                }
                if(!empty($new_dates['date_end'])){
                    $new_dates['date_end'] = Carbon::createFromFormat('d/m/yy', $new_dates['date_end']);
                }
                $values = array_merge(
                    $new_dates,
                    ['package_id' => $package->id]
                );
                PackageDateRange::create($values);
            }
            if ($update_dates) {
                foreach ($update_dates as $id => $date) {
                    if(!empty($date['date_start'])){
                        $date['date_start'] = Carbon::createFromFormat('d/m/yy', $date['date_start']);
                    }
                    if(!empty($date['date_end'])){
                        $date['date_end'] = Carbon::createFromFormat('d/m/yy', $date['date_end']);
                    }
                    $package->dateRanges()->where('id', $id)->update($date);
                }
            }

            DB::commit();
            Flash::success('Se actualizo con éxito los productos');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar actualizar los productos', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            Flash::error('Ocurrio un error al intentar actualizar los productos');
            DB::rollback();
            throw $ex;
        }
        return redirect(route('admin.packages.dateRange', $package->id));
    }

    public function photos($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');
            return redirect(route('admin.packages.index'));
        }

        return view('admin.packages.photos')
            ->with('package', $package);
    }

    public function videos($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');
            return redirect(route('admin.packages.index'));
        }

        return view('admin.packages.videos')
            ->with('package', $package);
    }

    public function uploadPhotos($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->packageRepository->findWithoutFail($id);

            if (empty($package)) {
                return Response::json(ResponseUtil::makeError('El paquete no se encontró'));
            }

            if ($request->get('upload_for', 'both') === 'both' || $request->get('upload_for', 'both') === 'desktop') {
                $this->uploadMultimediaPhotos($request, 'file', $package, Package::$GROUP_MULTIMEDIA);
            }
            if ($request->get('upload_for', 'both') === 'both' || $request->get('upload_for', 'both') === 'mobile') {
                $this->uploadMultimediaPhotos($request, 'file', $package, Package::$GROUP_MULTIMEDIA_MOBILE);
            }

            DB::commit();
            return Response::json(ResponseUtil::makeResponse('Se subió con éxito la foto', []));
        } catch (InvalidUploadException $ex) {
            DB::rollback();
            return Response::json(['success' => false, 'error' => $ex->getMessage()], 422);
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar subir la foto', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            DB::rollback();
            return Response::json(['success' => false, 'error' => 'No se pudo subir la foto'], 500);
        }
    }

    public function updateVideos($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->packageRepository->findWithoutFail($id);

            if (empty($package)) {
                return Response::json(ResponseUtil::makeError('El paquete no se encontró'));
            }


            //dd($request->all());
            if ($request->get('delete_video', null)) {
                $video = Video::find($request->get('delete_video', null));
                //dd($video);
                $video->delete();
                Log::info('Se borró con éxito el video en el paquete', ['url' => $video->url, 'name' => $package->name]);
                Flash::success('Se borró con éxito el video ' . $video->url);
            } else if ($request->get('video', null)) {
                $video = Video::find($request->get('video', null));
                $video->name = $request->get('name', 'name');
                $video->url = $request->get('url', 'url');
                $video->save();
                Log::info('Se actualizó con éxito el video en el paquete', ['url' => $video->url, 'name' => $package->name]);
                Flash::success('Se actualizó con éxito el video ' . $video->url);
            } else {
                $video = new Video([
                    'name' => $request->get('name', 'Nombre'),
                    'url' => $request->get('url', 'url'),
                ]);
                $package->attachVideo($video);
                Log::info('Se guardó con éxito el video en el paquete', ['url' => $request->get('url'), 'name' => $package->name]);
                Flash::success('Se guardó con éxito el video ' . $request->get('url'));
            }
            $package->save();
            DB::commit();
            return redirect(route('admin.packages.videos', [$package->id]));
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar actualizar los productos', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            Flash::error('Ocurrio un error al intentar guardar el video');
            DB::rollback();
            return redirect(route('admin.packages.videos', [$package->id]));
        }
    }

    public function updatePhotos($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->packageRepository->findWithoutFail($id);

            if (empty($package)) {
                Flash::error('El paquete no se encontró');

                return redirect(route('admin.packages.index'));
            }

            /*$media_names = collect($request->get('media_name', []));
            if($media_names->count()>0){
                $media_names->each(function($name, $id)use($package){
                    $media = $package->media->firstWhere('id', $id);
                    $media->name = $name;
                    $media->save();
                });
            }*/
            $delete_medias = collect($request->get('delete', []));
            if ($delete_medias->count() > 0) {
                $delete_medias->each(function ($id) use ($package) {
                    $media = $package->media->firstWhere('id', $id);
                    $package->detachMedia($media);
                });
            }

            DB::commit();
            Flash::success('Se actualizo con éxito los productos');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar actualizar los productos', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            Flash::error('Ocurrio un error al intentar actualizar los productos');
            DB::rollback();
            throw $ex;
        }
        return redirect(route('admin.packages.photos', $package->id));
    }

    /**
     * Display the specified Package.
     *
     * @param int $id
     *
     * @return Response
     */
    public function simulation($id)
    {
        $package = $this->packageSimulationRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('El paquete no se encontró');

            return redirect(route('admin.packages.index'));
        }

        return view('admin.packages.simulation')
            ->with('package', $package);
    }
}
