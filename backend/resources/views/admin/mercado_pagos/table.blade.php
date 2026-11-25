<table class="table table-responsive" id="mercadoPagos-table">
    <thead>
    <tr>

        <th>Orden</th>
        <th>Importe</th>
        <th>Descripción</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($mercadoPagos as $mercadoPago)
        <tr>
            <td>{!! App\Helpers\Helper::humanNameModel($mercadoPago->order_type)!!} -> {!! $mercadoPago->order_id!!}</td>
            <td>{!! App\Helpers\Helper::formatPrice($mercadoPago->transaction_amount)!!}</td>
            <td>{!! $mercadoPago->description!!}</td>
            <td>{!! $mercadoPago->payment_method_id!!}</td>
            <td>{!! $mercadoPago->status!!}</td>
            <td>
                @can('admin.mercadoPagos.show')<a href="{!! route('admin.mercadoPagos.show', [$mercadoPago->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan

            </td>
        </tr>
    @endforeach
    </tbody>
</table>