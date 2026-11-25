<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateInvoiceItemRequest;
use App\Http\Requests\Admin\UpdateInvoiceItemRequest;
use App\Repositories\Admin\InternalOrderItemRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class InternalOrderItemController extends AppBaseController
{
    /** @var  InternalOrderItemRepository */
    private $internalOrderItemRepository;

    public function __construct(InternalOrderItemRepository $internalOrderItemRepo)
    {
        $this->internalOrderItemRepository = $internalOrderItemRepo;
    }

    /**
     * Display a listing of the InvoiceItem.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->internalOrderItemRepository->pushCriteria(new RequestCriteria($request));
        $internalOrderItems = $this->internalOrderItemRepository->all();

        return view('admin.internal_order_items.index')
            ->with('internalOrderItems', $internalOrderItems);
    }

    /**
     * Show the form for creating a new InvoiceItem.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.internal_order_items.create');
    }

    /**
     * Store a newly created InvoiceItem in storage.
     *
     * @param CreateInvoiceItemRequest $request
     *
     * @return Response
     */
    public function store(CreateInvoiceItemRequest $request)
    {
        $input = $request->all();

        $internalOrderItem = $this->internalOrderItemRepository->create($input);

        Flash::success('Internal Order Item se guardó con éxito.');

        return redirect(route('admin.internalOrderItems.index'));
    }

    /**
     * Display the specified InvoiceItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $internalOrderItem = $this->internalOrderItemRepository->findWithoutFail($id);

        if (empty($internalOrderItem)) {
            Flash::error('Internal Order Item no se encontró');

            return redirect(route('admin.internalOrderItems.index'));
        }

        return view('admin.internal_order_items.show')->with('internalOrderItem', $internalOrderItem);
    }

    /**
     * Show the form for editing the specified InvoiceItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $internalOrderItem = $this->internalOrderItemRepository->findWithoutFail($id);

        if (empty($internalOrderItem)) {
            Flash::error('Internal Order Item no se encontró');

            return redirect(route('admin.internalOrderItems.index'));
        }

        return view('admin.internal_order_items.edit')->with('internalOrderItem', $internalOrderItem);
    }

    /**
     * Update the specified InvoiceItem in storage.
     *
     * @param  int              $id
     * @param UpdateInvoiceItemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvoiceItemRequest $request)
    {
        $internalOrderItem = $this->internalOrderItemRepository->findWithoutFail($id);

        if (empty($internalOrderItem)) {
            Flash::error('Internal Order Item no se encontró');

            return redirect(route('admin.internalOrderItems.index'));
        }

        $internalOrderItem = $this->internalOrderItemRepository->update($request->all(), $id);

        Flash::success('Internal Order Item se actualizó con éxito.');

        return redirect(route('admin.internalOrderItems.index'));
    }

    /**
     * Remove the specified InvoiceItem from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $internalOrderItem = $this->internalOrderItemRepository->findWithoutFail($id);

        if (empty($internalOrderItem)) {
            Flash::error('Internal Order Item no se encontró');

            return redirect(route('admin.internalOrderItems.index'));
        }

        $this->internalOrderItemRepository->delete($id);

        Flash::success('Internal Order Item se eliminó con éxito.');

        return redirect(route('admin.internalOrderItems.index'));
    }
}
