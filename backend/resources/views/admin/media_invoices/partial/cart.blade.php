<?php
$cart = \App\Models\Admin\MediaInvoice::getCart();
?>
@if(isset($cart))
    <div class="pull-right">
        <a href="{!! route('admin.media_invoices.confirm', $cart->id) !!}" class="label label-success">
            {!! App\Helpers\Helper::formatPrice($cart->total) !!}
            <i class="fa fa-shopping-cart"></i>
            <span class="badge badge-light">{!! ($cart->items_count) !!}</span>&nbsp;
        </a>
    </div>
@endif