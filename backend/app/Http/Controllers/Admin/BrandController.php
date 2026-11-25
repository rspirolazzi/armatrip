<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\AttachMedia;
use App\Http\Requests\Admin\CreateBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Admin\Brand;
use App\Repositories\Admin\BrandRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class BrandController extends AppBaseController
{
    use AttachMedia;
    /** @var  BrandRepository */
    private $brandRepository;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->brandRepository = $brandRepo;
    }

    /**
     * Display a listing of the Brand.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->brandRepository->pushCriteria(new RequestCriteria($request));
        $brands = $this->brandRepository->all();

        return view('admin.brands.index')
            ->with('brands', $brands);
    }

    /**
     * Show the form for creating a new Brand.
     *
     * @return Response
     */
    public function create()
    {
        $order = $this->brandRepository->getMaxOrder();
        return view('admin.brands.create')->with('order', $order);
    }

    /**
     * Store a newly created Brand in storage.
     *
     * @param CreateBrandRequest $request
     *
     * @return Response
     */
    public function store(CreateBrandRequest $request)
    {
        $input = $request->all();

        $brand = $this->brandRepository->create($input);
        $this->uploadImage($request, 'logo', $brand, Brand::$GROUP_LOGO);
        Flash::success('Marca se guardó con éxito.');

        return redirect(route('admin.brands.index'));
    }

    /**
     * Display the specified Brand.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('Marcano se encontro');

            return redirect(route('admin.brands.index'));
        }

        return view('admin.brands.show')->with('brand', $brand);
    }

    /**
     * Show the form for editing the specified Brand.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('Marcano se encontro');

            return redirect(route('admin.brands.index'));
        }

        return view('admin.brands.edit')->with('brand', $brand);
    }

    /**
     * Update the specified Brand in storage.
     *
     * @param  int $id
     * @param UpdateBrandRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBrandRequest $request)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('Marcano se encontro');

            return redirect(route('admin.brands.index'));
        }

        $brand = $this->brandRepository->update($request->all(), $id);
        $this->uploadImage($request, 'logo', $brand, Brand::$GROUP_LOGO);
        Flash::success('Marca se actualizó con éxito.');

        return redirect(route('admin.brands.index'));
    }

    /**
     * Remove the specified Brand from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('Marcano se encontro');

            return redirect(route('admin.brands.index'));
        }

        $this->brandRepository->delete($id);

        Flash::success('Marca se borró con éxito.');

        return redirect(route('admin.brands.index'));
    }
}
