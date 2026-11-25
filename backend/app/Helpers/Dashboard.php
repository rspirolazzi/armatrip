<?php

namespace App\Helpers;

use App\Models\Order;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 11/2/2018
 * Time: 10:42 PM
 */
class Dashboard
{
    protected static $month_names = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];
    protected static $colors = [
        [
            'fillColor' => 'rgb(210, 214, 222)',
            'strokeColor' => 'rgb(210, 214, 222)',
            'pointColor' => 'rgb(210, 214, 222)',
            'pointStrokeColor' => '#c1c7d1',
            'pointHighlightFill' => '#fff',
            'pointHighlightStroke' => 'rgb(220,220,220)',
        ],
        [
            'fillColor' => 'rgba(60,141,188,0.9)',
            'strokeColor' => 'rgba(60,141,188,0.8)',
            'pointColor' => '#3b8bba',
            'pointStrokeColor' => 'rgba(60,141,188,1)',
            'pointHighlightFill' => '#fff',
            'pointHighlightStroke' => 'rgba(60,141,188,1)',
        ]
    ];

    public static function formatChartSale($orders)
    {
        $orders = $orders->map(function ($order) {
            $order->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at);
            return $order;
        })->groupBy(function ($order) {
            return $order->updated_at->month;
        });
        $result = [
            'labels' => $orders->map(function ($order, $month) {
                return self::$month_names[$month];
            })->toArray(),
            'datasets' => $orders->map(function ($order) {
                $index = 0;
                $dataset = [];
                $colors = self::$colors[$index];
                return array_merge(
                    ['label' => '', 'dataset' => $dataset],
                    $colors
                );
            })->toArray()
        ];
        return $result;
    }

    public static function formatLastSale($orders)
    {
        $orders = $orders->map(function ($order) {
            $order->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at);
            $order->number = Order::getNumber($order->store_id, $order->id);
            $order->type = '';
            $order->label = __('messages.' . $order->status);
            switch ($order->status) {
                case Order::$STATUS_FINISHED:
                    $order->type = 'success';
                    break;
                case Order::$STATUS_PENDING:
                    $order->type = 'warning';
                    break;
                case Order::$STATUS_CANCELLED:
                    $order->type = 'danger';
                    break;
            }
            return $order;
        });
        return $orders;
    }

    public static function formatLastSalePackagesOrders($packages)
    {
        return $packages;
    }
}