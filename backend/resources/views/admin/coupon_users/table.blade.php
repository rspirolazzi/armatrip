<table class="table table-responsive" id="coupons-table">
    <thead>
    <tr>
        <th>Usuario</th>
        <th>Cupon</th>
        <th>Beneficio</th>
        <th>Orden</th>
        <th>Descuento</th>
        <th>Fecha de uso</th>
    </tr>
    </thead>
    <tbody>
    @foreach($couponUsers as $couponUser)
        <tr>
            <td>@can('admin.users.show')<a href="{!! route('admin.users.show', [$couponUser->user_id]) !!}">{!! trim($couponUser->user_name ) !!} ({!! $couponUser->user_email !!})</a>@endcan</td>
            <td>{!! $couponUser->coupon_name !!}</td>
            <td>{!! $couponUser->coupon_reward !!}%</td>
            <td>@can('admin.orders.show')<a href="{!! route('admin.orders.show', [$couponUser->order_id]) !!}">{!! \App\Models\Order::getNumber($couponUser->order_store_id, $couponUser->order_id) !!}</a>@endcan</td>
            <td>{!! App\Helpers\Helper::formatPrice($couponUser->order_discount)!!}</td>
            <td>{!! Carbon\Carbon::createFromTimeString($couponUser->used_at)->format('d/m/Y H:i')!!}</td>
        </tr>
    @endforeach
    </tbody>
</table>