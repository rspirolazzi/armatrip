<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateOrderItemRequest;
use App\Http\Requests\Admin\UpdateOrderItemRequest;
use App\Repositories\Admin\OrderItemRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class OrderItemController extends AppBaseController
{
    /** @var  OrderItemRepository */
    private $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepo)
    {
        $this->orderItemRepository = $orderItemRepo;
    }

    /**
     * Display a listing of the OrderItem.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->orderItemRepository->pushCriteria(new RequestCriteria($request));
        $orderItems = $this->orderItemRepository->paginate(25);

        return view('admin.order_items.index')
            ->with('orderItems', $orderItems);
    }

    /**
     * Show the form for creating a new OrderItem.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.order_items.create');
    }

    /**
     * Store a newly created OrderItem in storage.
     *
     * @param CreateOrderItemRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderItemRequest $request)
    {
        $input = $request->all();

        $orderItem = $this->orderItemRepository->create($input);

        Flash::success('Order Item se guardó con éxito.');

        return redirect(route('admin.orderItems.index'));
    }

    /**
     * Display the specified OrderItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $orderItem = $this->orderItemRepository->findWithoutFail($id);

        if (empty($orderItem)) {
            Flash::error('La orden Item no se encontró');

            return redirect(route('admin.orderItems.index'));
        }

        return view('admin.order_items.show')->with('orderItem', $orderItem);
    }

    /**
     * Show the form for editing the specified OrderItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $orderItem = $this->orderItemRepository->findWithoutFail($id);

        if (empty($orderItem)) {
            Flash::error('La orden Item no se encontró');

            return redirect(route('admin.orderItems.index'));
        }

        return view('admin.order_items.edit')->with('orderItem', $orderItem);
    }

    /**
     * Update the specified OrderItem in storage.
     *
     * @param  int              $id
     * @param UpdateOrderItemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderItemRequest $request)
    {
        $orderItem = $this->orderItemRepository->findWithoutFail($id);

        if (empty($orderItem)) {
            Flash::error('La orden Item no se encontró');

            return redirect(route('admin.orderItems.index'));
        }

        $orderItem = $this->orderItemRepository->update($request->all(), $id);

        Flash::success('Order Item se actualizó con éxito.');

        return redirect(route('admin.orderItems.index'));
    }

    /**
     * Remove the specified OrderItem from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $orderItem = $this->orderItemRepository->findWithoutFail($id);

        if (empty($orderItem)) {
            Flash::error('La orden Item no se encontró');

            return redirect(route('admin.orderItems.index'));
        }

        $this->orderItemRepository->delete($id);

        Flash::success('Order Item se eliminó con éxito.');

        return redirect(route('admin.orderItems.index'));
    }
}
