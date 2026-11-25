<?php

namespace App\Http\Controllers\Admin\IDG;

use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Item;
use App\Models\Admin\Product;
use App\Repositories\Admin\IDG\ProductRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ProductController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->productRepository->pushCriteria(new RequestCriteria($request));
        $this->productRepository->scopeQuery(function ($query) {
            return $query->where('allowed_to_sell', 1);
        });
        $products = $this->productRepository->paginate(25);

        return view('admin.idg_products.index')
            ->with('products', $products);
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        $order = Product::max('order');
        $order = is_null($order) ? 0 : $order + 1;
        return view('admin.idg_products.create')
            ->with('order', $order);
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateProductRequest $request)
    {
        $input = $request->all();
        $input['image_primary'] = $this->processImage($request, 'image_primary');
        $input['image_secondary'] = $this->processImage($request, 'image_secondary');

        $product = $this->productRepository->create($input);

        Flash::success('Producto guardado con éxito.');

        return redirect(route('idg.products.index'));
    }

    /**
     * Display the specified Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Producto no encontrado');

            return redirect(route('idg.products.index'));
        }

        return view('admin.idg_products.show')->with('product', $product);
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Producto no encontrado');

            return redirect(route('idg.products.index'));
        }

        return view('admin.idg_products.edit')->with('product', $product);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param  int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateProductRequest $request)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Producto no encontrado');

            return redirect(route('idg.products.index'));
        }
        $inputs = $request->all();
        $inputs['image_primary'] = $this->processImage($request, 'image_primary', $product->image_primary);
        $inputs['image_secondary'] = $this->processImage($request, 'image_secondary', $product->image_secondary);
        //dd($inputs);
        $product = $this->productRepository->update($inputs, $id);

        Flash::success('Producto actualizado con éxito.');

        return redirect(route('idg.products.index'));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Producto no encontrado');

            return redirect(route('idg.products.index'));
        }

        $this->productRepository->delete($id);

        Flash::success('Producto deleted con éxito.');

        return redirect(route('idg.products.index'));
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function items($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Producto no encontrado');

            return redirect(route('idg.products.index'));
        }
        $items = [];
        if ($product->category) {
            $items = Item::where('category_id', $product->category->id)->get();
            //dd($product->category, $items->groupBy());
        } else {
            $items = Item::all();
        }

        return view('admin.idg_products.items')
            ->with('product', $product)
            ->with('items', $items);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return Response
     */
    public function updateItems($id, Request $request)
    {
        try {
            $product = $this->productRepository->findWithoutFail($id);

            if (empty($product)) {
                Flash::error('Producto no encontrado');

                return redirect(route('idg.products.index'));
            }
            $itemsToDelete = $product->items->whereNotIn('id', $request->get('item'));
            $itemsToIgnore = $product->items->whereIn('id', $request->get('item'));

            foreach ($itemsToDelete->all() as $toDelete) {
                Log::debug('Quitando sabor', $toDelete->toArray());
                $product->items()->detach($toDelete->id);
            }

            foreach ($request->get('item') as $itemId) {
                if (is_null($itemsToIgnore->firstWhere('id', $itemId))) {
                    Log::debug('Agrenado sabor id', ['id' => $id]);
                    $product->items()->attach($itemId);
                }
            }
            Flash::success('Se actualizo con éxito los sabores');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar actualizar los sabores', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            Flash::error('Ocurrio un error al intentar actualizar los sabores');
        }
        return redirect(route('idg.products.items', [$id]));
    }

    protected function processImage(Request $request, $inputName, $default = null)
    {
        $filename = $default;
        try {
            if ($request->get('_delete_' . $inputName, false)) {
                $filename = config('armatrip.armatrip_in.multimedia.directory') . $request->get('_delete_' . $inputName, '');
                if (file_exists($filename)) {
                    Storage::delete($filename);
                    Log::info('Se borro el archivo' . $filename);
                }
                $filename = null;
            } else {
                if ($request->get('option_' . $inputName, null) === 'file') {
                    $file = $request->file('file_' . $inputName);
                    if ($file) {
                        $this->validate($request, ['file_' . $inputName => 'mimes:jpeg,png,jpg,gif,svg,pdf',]);
                        $directory = 'img';
                        if ($file->getMimeType() === 'application/pdf') {
                            $directory = 'pdf';
                        }
                        $directory = config('armatrip.armatrip_in.multimedia.directory') . $directory;
                        $filename = Storage::putFile('public' .  $directory, $file);
                        $filename = str_replace('public'.DIRECTORY_SEPARATOR, '', $filename);
                    }
                } else if ($request->get('option_' . $inputName, null) === 'url') {
                    $this->validate($request, ['url_' . $inputName => 'url',]);
                    $filename = $request->get('url_' . $inputName);
                }
            }
            //dd($filename, $inputName, $request->toArray());
        } catch (\Exception $e) {
            Log::error('Ocurrio un error al intentar procesar la imagen');
            dd($e);
        }
        return $filename;
    }
}
