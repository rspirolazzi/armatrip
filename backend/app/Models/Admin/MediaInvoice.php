<?php

namespace App\Models\Admin;

use App\Models\Admin\Product;
use Auth;
use Log;

/**
 * Class MediaInvoice
 * @package App\Models\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @property integer user_id
 */
class MediaInvoice extends Invoice
{

    public static $PAYMENT_CASH='cash';
    public static $PAYMENT_TRANSFER='transfer';
    public static $PAYMENT_CARD='card';

    protected static $_CART = null;

    public function items()
    {
        return $this->hasMany(MediaInvoiceItem::class, 'invoice_id');
    }

    public static function getCart()
    {
        if (self::$_CART === null) {
            Log::debug('El carrito no existe en cache así que lo busco');
            $user = Auth::user();
            self::$_CART = self::where([
                'user_id' => $user->id,
                'status' => self::$STATUS_DRAFT,
                'model_type' => self::class,
            ])->withCount('items')->first();
        }
        return self::$_CART;
    }

    public static function hasItem($product_id)
    {
        $cart = self::getCart();
        if ($cart) {
            return $cart->items()->where(['model_id' => $product_id, 'model_type' => Product::class])->first();
        } else {
            return null;
        }
    }
}
