<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Http\Requests\API\UpdateProductAPIRequest;
use App\Models\Admin\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */
class ProductAPIController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->productRepository->pushCriteria(new RequestCriteria($request));
        $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->productRepository->scopeQuery(function ($query) {
            return $query
                ->where('active', true)
                ->whereNull('parent_id')
                ;
        });
        $products = $this->productRepository->all();

        return $this->sendResponse($products->toArray());
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param $code
     * @return Response
     */
    public function show($code)
    {
        $product = $this->productRepository->findByCodeWithoutFail($code);

        if (empty($product)) {
            return$this->sendError('No se encontro el producto');
        }


        return $this->sendResponse($product->toArray());
    }
}
