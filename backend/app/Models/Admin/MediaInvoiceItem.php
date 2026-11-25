<?php

namespace App\Models\Admin;

/**
 * Class MediaInvoiceItem
 * @package App\Models\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @property integer product_id
 * @property integer qty
 */
class MediaInvoiceItem extends InvoiceItem
{
    public function getTotalAttribute()
    {
        try {
            return (float)$this->price * (int)$this->qty;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
