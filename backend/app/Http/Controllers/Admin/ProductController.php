<?php

namespace App\Http\Controllers\Admin;

use App\Criteria\Admin\FilterRequestCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\FilterRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Admin\Product;
use App\Models\Item;
use App\Repositories\Admin\ProductRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ProductController extends AppBaseController
{
    /** @var  ProductRepository */
    protected $productRepository;

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
        Log::info('Request en index', $request->toArray());
        $this->productRepository->pushCriteria(new RequestCriteria($request));
        $this->productRepository->scopeQuery(function ($query) {
            return $query->where('allowed_to_sell', 0);
        });
        $products = $this->productRepository->paginate(25);

        return view('admin.products.index')
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
        return view('admin.products.create')
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

        $product = $this->productRepository->create($input);

        Flash::success('Producto guardado con éxito.');

        return redirect(route('admin.products.index'));
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

            return redirect(route('admin.products.index'));
        }

        return view('admin.products.show')->with('product', $product);
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

            return redirect(route('admin.products.index'));
        }

        return view('admin.products.edit')->with('product', $product);
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

            return redirect(route('admin.products.index'));
        }

        $product = $this->productRepository->update($request->all(), $id);

        Flash::success('Producto actualizado con éxito.');

        return redirect(route('admin.products.index'));
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

            return redirect(route('admin.products.index'));
        }

        $this->productRepository->delete($id);

        Flash::success('Producto deleted con éxito.');

        return redirect(route('admin.products.index'));
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

            return redirect(route('admin.products.index'));
        }
        $items = [];
        if ($product->category) {
            $items = Item::where('category_id', $product->category->id)->get();
            //dd($product->category, $items->groupBy());
        } else {
            $items = Item::all();
        }

        return view('admin.products.items')
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

                return redirect(route('admin.products.index'));
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
        return redirect(route('admin.products.items', [$id]));
    }
}
