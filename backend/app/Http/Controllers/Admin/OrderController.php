<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Admin\SettingManager;
use App\Repositories\Admin\OrderRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Exports\Admin\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends AppBaseController
{
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the Order.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $turismo_individual = SettingManager::get('store_code_individual', 'turismo_individual');

        $this->filterRepository($request);
        $orders = $this->orderRepository->paginate(25);
        return view('admin.orders.index')
            ->with('turismo_individual', $turismo_individual)
            ->with('orders', $orders)
            ->with('valueInSeconds', 15);
    }


    /**
     * Display the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('La cotización no se encontró');

            return redirect(route('admin.orders.index'));
        }
        $turismo_individual = SettingManager::get('store_code_individual', 'turismo_individual');
        return view('admin.orders.show')->with([
            'order' => $order,
            'turismo_individual' => $turismo_individual,
        ]);
    }

    /**
     * Display the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function email($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('La cotización no se encontró');

            return redirect(route('admin.orders.index'));
        }


        $products = collect($order->products->map(function ($row_product) {
            $product = $row_product->product()->withTrashed()->first();
            $category = $product->category()->withTrashed()->first();

            return [
                'name' => $category->name,
                'id' => $category->id,
                'order' => $category->order,
                'product' => $product,
            ];
        }))->sortBy('order')->groupBy('id')->all();

        //dd($products);


        return view('email.order')->with([
            'order' => $order,
            'products' => $products,
        ]);
    }

    public function print($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('La cotización no se encontró');

            return redirect(route('admin.orders.index'));
        }

        return view('admin.orders.print')->with([
            'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('La cotización no se encontró');

            return redirect(route('admin.orders.index'));
        }

        return view('admin.orders.edit')->with('order', $order);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateOrderRequest $request)
    {
        $inputs = $request->only('comments', 'status');
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('La cotización no se encontró');

            return redirect(route('admin.orders.index'));
        }
        $order = $this->orderRepository->update($inputs, $id);

        Flash::success('La cotización se actualizó con éxito.');

        return redirect(route('admin.orders.index'));
    }

    /**
     * Inicializa el orderRepository para tomar los parametros en la URL
     *
     * @param Request $request
     * @return void
     */
    protected function filterRepository(Request $request)
    {
        $this->orderRepository->pushCriteria(new RequestCriteria($request));
        $this->orderRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->orderRepository->scopeQuery(function ($query) use ($request) {
            if ($request->get(config('repository.criteria.params.orderBy', 'orderBy'), null) == null) {
                $query->orderBy('reserved_at', 'desc');
            }
            return $query;
        });
        $this->orderRepository->orderBy('created_at', 'desc');
    }

    public function export(Request $request)
    {
        $this->filterRepository($request);
        $orders = new OrderExport($this->orderRepository->limit(1500));
        return Excel::download($orders, 'ordenes.xlsx');
    }
}
