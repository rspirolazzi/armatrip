<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreatePackageProductRequest;
use App\Http\Requests\Admin\UpdatePackageProductRequest;
use App\Repositories\Admin\PackageProductRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PackageProductController extends AppBaseController
{
    /** @var  PackageProductRepository */
    private $packageProductRepository;

    public function __construct(PackageProductRepository $packageProductRepo)
    {
        $this->packageProductRepository = $packageProductRepo;
    }

    /**
     * Display a listing of the PackageProduct.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->packageProductRepository->pushCriteria(new RequestCriteria($request));
        $packageProducts = $this->packageProductRepository->all();

        return view('admin.package_products.index')
            ->with('packageProducts', $packageProducts);
    }

    /**
     * Show the form for creating a new PackageProduct.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.package_products.create');
    }

    /**
     * Store a newly created PackageProduct in storage.
     *
     * @param CreatePackageProductRequest $request
     *
     * @return Response
     */
    public function store(CreatePackageProductRequest $request)
    {
        $input = $request->all();

        $packageProduct = $this->packageProductRepository->create($input);

        Flash::success('El paquete se guardó con éxito.');

        return redirect(route('admin.packageProducts.index'));
    }

    /**
     * Display the specified PackageProduct.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $packageProduct = $this->packageProductRepository->findWithoutFail($id);

        if (empty($packageProduct)) {
            Flash::error('El paquete Product no se encontró');

            return redirect(route('admin.packageProducts.index'));
        }

        return view('admin.package_products.show')->with('packageProduct', $packageProduct);
    }

    /**
     * Show the form for editing the specified PackageProduct.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $packageProduct = $this->packageProductRepository->findWithoutFail($id);

        if (empty($packageProduct)) {
            Flash::error('El paquete Product no se encontró');

            return redirect(route('admin.packageProducts.index'));
        }

        return view('admin.package_products.edit')->with('packageProduct', $packageProduct);
    }

    /**
     * Update the specified PackageProduct in storage.
     *
     * @param  int              $id
     * @param UpdatePackageProductRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePackageProductRequest $request)
    {
        $packageProduct = $this->packageProductRepository->findWithoutFail($id);

        if (empty($packageProduct)) {
            Flash::error('El paquete Product no se encontró');

            return redirect(route('admin.packageProducts.index'));
        }

        $packageProduct = $this->packageProductRepository->update($request->all(), $id);

        Flash::success('El paquete se actualizó con éxito.');

        return redirect(route('admin.packageProducts.index'));
    }

    /**
     * Remove the specified PackageProduct from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $packageProduct = $this->packageProductRepository->findWithoutFail($id);

        if (empty($packageProduct)) {
            Flash::error('El paquete Product no se encontró');

            return redirect(route('admin.packageProducts.index'));
        }

        $this->packageProductRepository->delete($id);

        Flash::success('El paquete se eliminó con éxito.');

        return redirect(route('admin.packageProducts.index'));
    }
}
