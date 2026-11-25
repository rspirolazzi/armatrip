<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateInvoiceRequest;
use App\Http\Requests\Admin\UpdateInvoiceRequest;
use App\Models\Item;
use App\Models\Admin\Product;
use App\Repositories\Admin\InternalOrderRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class InternalOrderController extends AppBaseController
{
    /** @var  InternalOrderRepository */
    private $internalOrderRepository;

    public function __construct(InternalOrderRepository $internalOrderRepo)
    {
        $this->internalOrderRepository = $internalOrderRepo;
    }

    /**
     * Display a listing of the Invoice.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        dd(Auth::user());
        //$this->internalOrderRepository->pushCriteria(new RequestCriteria($request));
        //$internalOrders = $this->internalOrderRepository->all();
        $internalOrders = [];

        return view('admin.internal_orders.index')
            ->with('internalOrders', $internalOrders);
    }

    /**
     * Show the form for creating a new Invoice.
     *
     * @return Response
     */
    public function create()
    {
        $products = Product::all();
        $items = Item::all();
        //dd($products, $items);
        return view('admin.internal_orders.create')->with('items', $items);
    }

    /**
     * Store a newly created Invoice in storage.
     *
     * @param CreateInvoiceRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateInvoiceRequest $request)
    {
        $input = $request->all();

        $internalOrder = $this->internalOrderRepository->create($input);

        Flash::success('La orden interna se guardó con éxito.');

        return redirect(route('admin.internalOrders.index'));
    }

    /**
     * Display the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $internalOrder = $this->internalOrderRepository->findWithoutFail($id);

        if (empty($internalOrder)) {
            Flash::error('Internal Order no se encontró');

            return redirect(route('admin.internalOrders.index'));
        }

        return view('admin.internal_orders.show')->with('internalOrder', $internalOrder);
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $internalOrder = $this->internalOrderRepository->findWithoutFail($id);

        if (empty($internalOrder)) {
            Flash::error('Internal Order no se encontró');

            return redirect(route('admin.internalOrders.index'));
        }

        return view('admin.internal_orders.edit')->with('internalOrder', $internalOrder);
    }

    /**
     * Update the specified Invoice in storage.
     *
     * @param  int $id
     * @param UpdateInvoiceRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateInvoiceRequest $request)
    {
        $internalOrder = $this->internalOrderRepository->findWithoutFail($id);

        if (empty($internalOrder)) {
            Flash::error('Internal Order no se encontró');

            return redirect(route('admin.internalOrders.index'));
        }

        $internalOrder = $this->internalOrderRepository->update($request->all(), $id);

        Flash::success('La orden interna se actualizó con éxito.');

        return redirect(route('admin.internalOrders.index'));
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $internalOrder = $this->internalOrderRepository->findWithoutFail($id);

        if (empty($internalOrder)) {
            Flash::error('Internal Order no se encontró');

            return redirect(route('admin.internalOrders.index'));
        }

        $this->internalOrderRepository->delete($id);

        Flash::success('La orden interna se eliminó con éxito.');

        return redirect(route('admin.internalOrders.index'));
    }
}
