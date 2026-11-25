<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-05-26
 * Time: 9:13 p.m.
 */

namespace App\Services;


use App\Models\Admin\Package;

class PackageService
{

    protected $package;

    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    /**
     * @return Package
     */
    public function getPackage(): Package
    {
        return $this->package;
    }

    public function selectProducts($products_code_selected=[]){
        $products_from_package = $this->package->products;

        if (config('armatrip.show_disabled_categories', false)) {
            $products_from_package = $products_from_package->filter(function($r){
                return $r->category->active==1;
            });
        }

        if ($products_code_selected->count() > 0) {
            $return_products = $products_from_package->map(function ($product) use ($products_code_selected) {
                $default = collect($products_code_selected)->search($product->code, true);
                $value = array_merge($product->toArray(), ['default' => $default !== false ? true : false]);
                return $value;
            });
        } else {
            $return_products = $products_from_package->map(function ($product) {
                $default = $product->default;
                $value = array_merge($product->toArray(), ['default' => $default]);
                return $value;
            });
        }
        $this->package->products = $return_products;
    }




    public function pa(){
        try {

            $province = $order->province;
            $orderPackage = $order->package;
            $package = $orderPackage->package;
            $tender_installment = $orderPackage->installment;
            $tender_tax = $orderPackage->tax;

            $products_code_selected = collect($request->get('codes', []));
            $return_products = [];

            $products_from_package = $package->products;

            if (config('armatrip.show_disabled_categories', false)) {
                $products_from_package = $products_from_package->filter(function($r){
                    return $r->category->active==1;
                });
            }

            if ($products_code_selected->count() > 0) {
                $return_products = $products_from_package->map(function ($product) use ($products_code_selected) {
                    $default = collect($products_code_selected)->search($product->code, true);
                    $value = array_merge($product->toArray(), ['default' => $default !== false ? true : false]);
                    return $value;
                });
            } else {
                $return_products = $products_from_package->map(function ($product) {
                    $default = $product->default;
                    $value = array_merge($product->toArray(), ['default' => $default]);
                    return $value;
                });
            }

            $total_package_by_passager = $this->calculateTotalPriceByProducts($package->price, $return_products, $province);
            $total_package_by_passager = \App\Helpers\Helper::applyTax($total_package_by_passager, $tender_tax);

            $dateRange = $orderPackage->findDateRange();
            if ($dateRange) {
                $total_package_by_passager = \App\Helpers\Helper::applyTax($total_package_by_passager, $dateRange->tax);
            }

            $total_package = $orderPackage->qty * $total_package_by_passager;
            $price_by_passagers_tender = round($total_package_by_passager / $tender_installment, 2);

            $return_value = array_merge(
                $package->toArray(),
                [
                    'products' => $return_products->sortBy('order')->values()->all(),
                    'passagers' => $orderPackage->qty,
                    'installment' => $tender_installment,
                    'price_by_passagers' => $total_package_by_passager,
                    'price_by_passagers_tender' => $price_by_passagers_tender,
                    'total' => $total_package,
                ]
            );
            $orderPackage->price = $total_package_by_passager;
            $orderPackage->total = $total_package;
            $orderPackage->save();
            $order->total = $total_package;
            $order->save();
            Log::debug('Order Paquete', $orderPackage->toArray());
            return $return_value;

        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al obtener getAllOptionsByOrder', [
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'code' => $ex->getCode(),
                'traceAsString' => $ex->getTraceAsString(),
            ]);
            throw $ex;
        }
    }
}