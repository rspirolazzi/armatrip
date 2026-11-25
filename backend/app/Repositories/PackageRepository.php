<?php

namespace App\Repositories;

use App\Models\Admin\Package;
use App\Models\Admin\Province;
use App\Models\Admin\SettingManager;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Common\BaseRepository;
use Coupons;

/**
 * Class PackageRepository
 * @package App\Repositories
 * @version March 4, 2018, 11:23 pm UTC
 *
 * @method Package findWithoutFail($id, $columns = ['*'])
 * @method Package find($id, $columns = ['*'])
 * @method Package first($columns = ['*'])
 */
class PackageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Package::class;
    }

    public function findByCodeWithoutFail($code, $columns = ['*'])
    {
        try {
            return $this->findByField('code', $code, $columns)->first();
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * Retrieve all data of repository
     *
     * @param null $province
     * @param array $columns
     *
     * @return mixed
     */
    public function allWithProvince($province = null, $columns = ['*'])
    {
        $packages = $this->all($columns);
        if ($province != null && $province->tax > 0) {
            $packages = $packages->map(function ($package) use ($province) {
                return $package->products->map(function ($product) use ($province) {
                    $product->province = $province;
                    return $product;
                });
            });
        }
        return $packages;
    }

    public function getAllOptionsByOrder(Order $order, Request $request)
    {
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
                $products_from_package = $products_from_package->filter(function ($r) {
                    return $r->category->active == 1;
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

            $total_package_by_passager = $this->calculateTotalPriceByProducts($package->finalPrice, $return_products, $province);
            $total_package_by_passager = \App\Helpers\Helper::applyTax($total_package_by_passager, $tender_tax);
            $total_package_by_passager = \App\Helpers\Helper::applyTax($total_package_by_passager, SettingManager::get('increment_percent', 0));

            $dateRange = $orderPackage->findDateRange();
            if ($dateRange) {
                $total_package_by_passager = \App\Helpers\Helper::applyTax($total_package_by_passager, $dateRange->tax);
            }

            $total_package = $orderPackage->qty * $total_package_by_passager;
            $price_by_passagers_tender = round($total_package_by_passager / $tender_installment, 2);


            $coupon = $order->coupon;
            if ($coupon) {
                $coupon = Coupons::check($order->coupon->code);
                if ($coupon) {
                    $discount = ($coupon->reward / 100);

                    $order->discount = $total_package * $discount;
                    $order->total = $total_package - $order->discount;

                    $total_package = $total_package - ($total_package*$discount);
                    $total_package_by_passager = $total_package_by_passager - ($total_package_by_passager*$discount);
                    $price_by_passagers_tender = $price_by_passagers_tender - ($price_by_passagers_tender*$discount);
                }
            }

            $return_value = array_merge(
                $package->toArray(),
                [
                    'products' => $return_products->sortBy('order')->values()->all(),
                    'passagers' => $orderPackage->qty,
                    'installment' => $tender_installment,
                    'price_by_passagers' => $total_package_by_passager,
                    'price_by_passagers_tender' => $price_by_passagers_tender,
                    'total' => $total_package,
                    'share_code'=>($package->shareable && $order->public_code_url) ? $order->public_code_url : '',
                    'coupon'=>$order->coupon ? [
                        'code'=>$order->coupon->code,
                        'name'=>$order->coupon->name,
                        'description'=>$order->coupon->description,
                    ] : null,
                ]
            );

            $orderPackage->price = $total_package_by_passager;
            $orderPackage->total = $total_package;
            $orderPackage->save();
            $order->total = $total_package;
            $order->save();

            if((bool)SettingManager::get('mp_enabled', false)){
                $percent = SettingManager::get('mp_advance_amount_percentage', 0);
                if($percent > 0 ){
                    $return_value['amount_mp'] = round($orderPackage->price * ($percent/100));
                }
            }

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

    protected function calculateTotalPriceByProducts($price, $products, Province $province)
    {
        $total = collect($products)->filter(function ($product) {
            return $product['default'] === true;
        })->reduce(function ($total, $product) use ($province) {
            if ($province && $province->tax > 0 && $product['code'] == SettingManager::get('tax_product_code', config('armatrip.tax.product_code'))) {
                $tax = $product['price'] * ($province->tax / 100);
                $product['price'] = $tax + $product['price'];
            }
            return $total + $product['price'];
        }, $price);

        return $total;
    }
}
