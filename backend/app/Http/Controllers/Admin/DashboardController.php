<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Helpers\Dashboard;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Psy\debug;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    protected function getUsersCount()
    {
        $query = DB::table('users')
            ->select(DB::raw('COUNT(users.id) as count'))
            ->join('orders', 'orders.user_id', '=', 'users.id');
        $query = $this->scopeQueryRole($query, 'orders');
        return $query;
    }

    protected function getOrderCountByStatus()
    {
        $query = DB::table('orders')
            ->select('status', DB::raw('COUNT(orders.id) as count'))
            ->groupBy('status');
        $query = $this->scopeQueryRole($query, 'orders');
        return $query;
    }


    protected function scopeQueryRole($query, $table = '')
    {
        /*if (Auth::user()->hasRole(Admin::$ROLE_ADMIN)) {
            $query->where((!empty($table) ? $table . '.' : '') . 'store_id', Auth::user()->store_id);
        }*/
        return $query;
    }

    protected function reduceOrders($orders, $status = null)
    {
        if ($status) {
            $orders = $orders->filter(function ($order) use ($status) {
                return $order->status == $status;
            });
        }
        $total = $orders->reduce(function ($carry, $order) {
            return $carry + $order->count;
        });
        return $total;
    }

    protected function getSaleOrdersFromDates($start, $end)
    {
        $query = DB::table('orders')
            ->select(
                'updated_at',
                'total'
            //DB::raw('DATE_FORMAT(updated_at, \'%Y-%m\') as updated'),
            //DB::raw('SUM(orders.total) as total')
            )
            //->groupBy('updated')
            ->whereBetween('orders.updated_at', [$start, $end])
            ->where('status', Order::$STATUS_FINISHED);
        $query = $this->scopeQueryRole($query, 'orders');
        return $query;
    }

    protected function getLastOrders($limit = 10)
    {
        $query = DB::table('orders')
            ->select('orders.*', 'packages.name as package')
            ->join('order_packages', 'order_packages.order_id', '=', 'orders.id')
            ->join('packages', 'packages.id', '=', 'order_packages.package_id')
            //->where('status', Order::$STATUS_FINISHED)
            ->orderBy('orders.updated_at', 'desc')
            ->limit($limit);
        $query = $this->scopeQueryRole($query, 'orders');
        return $query;
    }

    protected function getLastSalePackagesOrders($start, $end, $limit=10)
    {
        $query = DB::table('packages')
            ->select('packages.name',
                DB::raw('SUM(order_packages.total) as total'))
            ->join('order_packages', 'order_packages.package_id', '=', 'packages.id')
            ->join('orders', 'order_packages.order_id', '=', 'orders.id')
            ->orderBy('orders.updated_at', 'desc')
            ->groupBy('packages.name', 'orders.updated_at')
            ->where('orders.status', Order::$STATUS_FINISHED)
            ->whereBetween('orders.updated_at', [$start, $end])
            ->limit($limit)
        ;
        $query = $this->scopeQueryRole($query, 'orders');
        return $query;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users_count = 0;
        $order_count = 0;
        $order_confirm_count = 0;
        $order_pending_count = 0;
        try {
            $users_count = $this->getUsersCount()->first()->count;
            $orders = $this->getOrderCountByStatus();
            $order_count = $this->reduceOrders($orders->get());
            $order_confirm_count = $this->reduceOrders($orders->get(), Order::$STATUS_FINISHED);
            $order_pending_count = $this->reduceOrders($orders->get(), Order::$STATUS_PENDING);
            Log::debug('Se calcularon los totales', [
                'users_count' => $users_count,
                'orders' => $orders,
                'order_count' => $order_count,
                'order_confirm_count' => $order_confirm_count,
                'order_pending_count' => $order_pending_count,
            ]);
        } catch (\Exception $e) {
            Log::error('Calculos de cantidades:Ocurrio un error al calcular los reportes', [$e->__toString()]);
            //dd($e);
        }
        $months_sales_start_date = Carbon::now()->subDays(30 * 6);
        $months_sales_end_date = Carbon::now();

        $last_six_months_of_orders = null;

        try {
            $orders = $this->getSaleOrdersFromDates($months_sales_start_date, $months_sales_end_date);
            $last_six_months_of_orders = Dashboard::formatChartSale($orders->get());

        } catch (\Exception $e) {
            Log::error('Calculos de ventas:Ocurrio un error al calcular las ventas', [$e->__toString()]);
            $last_six_months_of_orders = null;
            //dd($e);
        }
        $last_orders = null;
        try {
            $last_orders = Dashboard::formatLastSale($this->getLastOrders()->get());
        } catch (\Exception $e) {
            Log::error('Calculos de ventas:Ocurrio un error al calcular las ventas', [$e->__toString()]);
            $last_orders = null;
            dd($e);
        }
        $last_sold_packages = null;
        try {
            $last_sold_packages = Dashboard::formatLastSalePackagesOrders($this->getLastSalePackagesOrders($months_sales_start_date, $months_sales_end_date)->get());
        } catch (\Exception $e) {
            Log::error('Calculos de packageos :Ocurrio un error al calcular las ventas de packageos', [$e->__toString()]);
            $last_sold_packages = null;
            dd($e);
        }

        return view('admin.dashboard.index')
            ->with('users_count', $users_count)
            ->with('order_count', $order_count)
            ->with('order_confirm_count', $order_confirm_count)
            ->with('order_pending_count', $order_pending_count)
            ->with('months_sales_start_date', $months_sales_start_date)
            ->with('months_sales_end_date', $months_sales_end_date)
            //->with('last_six_months_of_orders', $last_six_months_of_orders)
            ->with('last_orders', $last_orders)
            ->with('last_sold_packages', $last_sold_packages);
    }
}
