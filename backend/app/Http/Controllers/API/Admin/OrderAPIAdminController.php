<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Response;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class OrderAPIAdminController extends AppBaseController
{
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the PackageGroup.
     * GET|HEAD /packageGroups
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $orders = $this->orderRepository->paginate(25)->map(function($row){
            return $row->toArrayAdmin();
        });
        return $this->sendResponse($orders, 'Orders retrieved successfully');
    }



    /**
     * Display the specified PackageGroup.
     * GET|HEAD /packageGroups/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PackageGroup $order */
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        return $this->sendResponse($order->toArrayAdmin(), 'Order retrieved successfully');
    }

}
