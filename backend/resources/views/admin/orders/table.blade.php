<table class="table table-responsive" id="orders-table">
    <thead>
    <tr>
        <th class="">Cod</th>
        <th class="">Fecha/Hora</th>
        <th class="hidden-xs">Usuario</th>
        <th class="hidden-xs">Empresa</th>
        <th class="">Total</th>
        <th class="text-center hidden-xs">Estado</th>
        <th class="text-center hidden-xs"></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr class="{!! $turismo_individual == (isset($order->store)?$order->store->code : '') ? 'info' : '' !!}">
            <td class="">{!! $order->number !!}</td>
            <td class="">{!! isset($order->reserved_at) ? $order->reserved_at->format('d/m/Y H:i') :  $order->updated_at->format('d/m/Y H:i') !!}</td>
            <td class="hidden-xs">{!! \Illuminate\Support\Str::limit($order->user->email, 30) !!}</td>
            <td class="hidden-xs">{!! (isset($order->store)?$order->store->name : '') !!}</td>
            <td class="order-price">{!! App\Helpers\Helper::formatPrice($order->total) !!}</td>
            <td class="text-center hidden-xs">
                @if($order->status == \App\Models\Order::$STATUS_PENDING)
                    <span class="label label-warning">Pendiente</span>
                @elseif($order->status == \App\Models\Order::$STATUS_FINISHED)
                    <span class="label label-success">Finalizado</span>
                @elseif($order->status == \App\Models\Order::$STATUS_CANCELLED)
                    <span class="label label-danger">Cancelado</span>
                @elseif($order->status == \App\Models\Order::$STATUS_SOLD)
                    <span class="label label-success">Vendido</span>
                @elseif($order->status == \App\Models\Order::$STATUS_CONTACTED)
                    <span class="label label-warning">Contactado</span>
                @else
                    <span class="label label-default">N/A</span>
                @endif
            </td>
            <td class="text-center hidden-xs">
                @if(isset($order->mercadoPago))
                    <?php
                    $title = '';
                    $type = '';
                    switch ($order->mercadoPago->status) {
                        case (\App\Models\MercadoPago::$STATUS_APPROVED):
                            $title = 'El pago de la reserva esta aprobada';
                            $type = 'success';
                            break;
                        case (\App\Models\MercadoPago::$STATUS_IN_PROCESS):
                            $title = 'El pago de la reserva esta en proceso de aprobación';
                            $type = 'warning';
                            break;
                        case (\App\Models\MercadoPago::$STATUS_REJECTED):
                            $title = 'El pago de la reserva fue rechazado';
                            $type = 'danger';
                            break;
                        case (\App\Models\MercadoPago::$STATUS_EXCEPTION):
                            $title = 'Ocurrio un error inesperado al procesar el pago de la reserva';
                            $type = 'danger';
                            break;
                    }
                    ?>
                    <span class="fa-stack fa-lg" title="{!!$title!!}" alt="{!!$title!!}">
                        <i class="fa fa-usd fa-stack-1x"></i>
                        <i class="fa fa-circle-o fa-stack-2x text-{!!$type!!}"></i>
                    </span>
                @endif
            </td>
            <td class="text-center">
                <div class='btn-group'>
                    @can('admin.orders.show')
                        <a href="{!! route('admin.orders.show', [$order->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-eye fa-3x"></i></a>
                    @endcan
                    @can('admin.orders.edit')
                        <a href="{!! route('admin.orders.edit', [$order->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-pencil fa-3x"></i></a>
                    @endcan
                </div>
            </td>
            {{--<td class="text-center">
                <div class='btn-group'>
                    <a target="_tab" href="{!! route('admin.orders.print', [$order->id]) !!}" class='btn btn-default btn-xs' style="font-size: 18px;padding: 5px 10px;border-radius: 10px;font-weight: 600">IMPRIMIR</a>
                </div>
            </td>--}}
        </tr>
    @endforeach
    </tbody>
</table>