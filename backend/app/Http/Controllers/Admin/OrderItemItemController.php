<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateOrderItemItemRequest;
use App\Http\Requests\Admin\UpdateOrderItemItemRequest;
use App\Repositories\Admin\OrderItemItemRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class OrderItemItemController extends AppBaseController
{
    /** @var  OrderItemItemRepository */
    private $orderItemTagRepository;

    public function __construct(OrderItemItemRepository $orderItemTagRepo)
    {
        $this->orderItemTagRepository = $orderItemTagRepo;
    }

    /**
     * Display a listing of the OrderItemItem.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->orderItemTagRepository->pushCriteria(new RequestCriteria($request));
        $orderItemTags = $this->orderItemTagRepository->paginate(25);

        return view('admin.order_item_items.index')
            ->with('orderItemTags', $orderItemTags);
    }

    /**
     * Show the form for creating a new OrderItemItem.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.order_item_items.create');
    }

    /**
     * Store a newly created OrderItemItem in storage.
     *
     * @param CreateOrderItemItemRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderItemItemRequest $request)
    {
        $input = $request->all();

        $orderItemTag = $this->orderItemTagRepository->create($input);

        Flash::success('Order Item Tag se guardó con éxito.');

        return redirect(route('admin.orderItemTags.index'));
    }

    /**
     * Display the specified OrderItemItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $orderItemTag = $this->orderItemTagRepository->findWithoutFail($id);

        if (empty($orderItemTag)) {
            Flash::error('La orden Item Tag no se encontró');

            return redirect(route('admin.orderItemTags.index'));
        }

        return view('admin.order_item_items.show')->with('orderItemTag', $orderItemTag);
    }

    /**
     * Show the form for editing the specified OrderItemItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $orderItemTag = $this->orderItemTagRepository->findWithoutFail($id);

        if (empty($orderItemTag)) {
            Flash::error('La orden Item Tag no se encontró');

            return redirect(route('admin.orderItemTags.index'));
        }

        return view('admin.order_item_items.edit')->with('orderItemTag', $orderItemTag);
    }

    /**
     * Update the specified OrderItemItem in storage.
     *
     * @param  int              $id
     * @param UpdateOrderItemItemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderItemItemRequest $request)
    {
        $orderItemTag = $this->orderItemTagRepository->findWithoutFail($id);

        if (empty($orderItemTag)) {
            Flash::error('La orden Item Tag no se encontró');

            return redirect(route('admin.orderItemTags.index'));
        }

        $orderItemTag = $this->orderItemTagRepository->update($request->all(), $id);

        Flash::success('Order Item Tag se actualizó con éxito.');

        return redirect(route('admin.orderItemTags.index'));
    }

    /**
     * Remove the specified OrderItemItem from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $orderItemTag = $this->orderItemTagRepository->findWithoutFail($id);

        if (empty($orderItemTag)) {
            Flash::error('La orden Item Tag no se encontró');

            return redirect(route('admin.orderItemTags.index'));
        }

        $this->orderItemTagRepository->delete($id);

        Flash::success('Order Item Tag se eliminó con éxito.');

        return redirect(route('admin.orderItemTags.index'));
    }
}
