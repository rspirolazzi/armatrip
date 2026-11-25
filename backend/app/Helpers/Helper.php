<?php

namespace App\Helpers;

use App\Models\Item;
use Storage;

/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 11/2/2018
 * Time: 10:42 PM
 */
class Helper
{
    public static function formatPrice($price)
    {
        // Do your necessary logic
        return sprintf("\$ %02.0f", $price);
    }

    public static function formatFloat($price)
    {
        // Do your necessary logic
        return sprintf("%02.2f", $price);
    }

    public static function applyTax($value, $tax)
    {
        $tax = $tax ? ($tax / 100) : 0;
        $total = 0;
        if ($tax < 0) {
            $total = $value - (abs($tax) * $value);
        } else {
            $total = $value + (abs($tax) * $value);
        }
        return $total;
    }
    public static function humanNameModel($modelName)
    {
        switch ($modelName) {
            case 'App\Models\Admin\MediaInvoice':
                $default = 'Pedido Interno';
                break;
            case 'App\Models\Order':
                $default = 'Reserva';
                break;
            default:
                $default = $modelName;
                break;
        }
        return $default;
    }

    public static function routeNameModelShow($modelName)
    {
        switch ($modelName) {
            case 'App\Models\Admin\MediaInvoice':
                $default = 'admin.media_invoices.show';
                break;
            case 'App\Models\Order':
                $default = 'admin.orders.show';
                break;
            default:
                $default = '';
                break;
        }
        return $default;
    }
}