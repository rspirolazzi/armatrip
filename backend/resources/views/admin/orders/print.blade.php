@extends('layouts.app')
@section('scripts')
    <script type="application/javascript">
        $(function(){
            print();
            function print(){
                var height = (($("#print").outerHeight() * 0.029) + 5 ) + "cm";
                var width = "8cm";
                var divToPrint=document.getElementById('print');
                var newWin=window.open('','Print-Window');

                newWin.document.open();

                newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><link rel="stylesheet" href="{!! env('APP_URL_FIX_IMAGES') !!}/css/comanda.css"><style>#print{display:block} @media print {@page{size: '+width+' '+height+';margin:0;}}</style><body onload="window.print();">'+divToPrint.innerHTML+'</body>');

                newWin.document.close();

                setTimeout(function(){newWin.close();},10);

                window.close();
            }
        })
    </script>
@endsection
@section('content')
    <div id="print">
        <div class="print-container">
            <div>
                <div class="text-center"><img src="{!! env('APP_URL_FIX_IMAGES') !!}/img/logo-mono.svg" width="120px"></div>
            </div>
            <h2 class="text-center">PEDIDO ONLINE N° {{ $order->number }}</h2>
            <div class="sucursal">{{ $address->street }},
                                                        {{ $address->number }},
                                                        {{ $address->city }},
                                                        {{ $address->state }},
                                                        Entre calles: {{ $address->line1 }}</div>
            <div class="cliente"><div>Cliente: {{ $user->name }} {{ $user->last_name }}</div></div>
            <ul class="list-unstyled">
                <li>Fecha/Hora de pedido: {{ $order->created_at }}</li>
                <li><strong>Fecha/Hora de envío: {{ $order->delivery_time }}</strong></li>
            </ul>
            <div class="linea-punteada" style="margin-top: 10px"></div>
            <h3 class="text-center">DETALLE DE PEDIDO</h3>
            @foreach($items as $index => $item)
                <ul class="list-unstyled list-productos">
                    <li><strong>{{ $item->product->name }}</strong></li>
                    <li>Cantidad: {{ $item->qty }}</li>
                    <li>
                        <div>Sabores: </div>
                        <ul class="list-unstyled">
                            <li>{{$item->items}}</li>
                        </ul>
                    </li>
                    <li>
                        <div class="pull-right"><strong>Subtotal: {{ $item->total }}</strong></div>
                    </li>
                </ul>
            @endforeach
            <div class="linea-punteada"></div>
            <div style="margin-top: 10px;"><strong>COMENTARIOS:</strong></div>
            <div style="margin-bottom: 20px;font-size: 12px;">{{ $order->comments }}</div>
            <div></div>
            <div class="linea-punteada" style="margin-bottom: 5px"></div>
            <div class="text-right"><strong> Subtotal: {{App\Helpers\Helper::formatPrice($order->sub_total) }}</strong></div>
            <div class="text-right"><strong> Delivery: {{App\Helpers\Helper::formatPrice($order->delivery) }}</strong></div>
            <h2 class="text-right" style="margin-top: 10px">Total: {{App\Helpers\Helper::formatPrice($order->total) }}</h2>
            @if($order->payment == \App\Models\Order::$PAYMENT_CASH)
                <div class="text-center"><strong>Forma de pago: Efectivo</strong></div>
                <div class="text-center"><strong>Abona con: {{App\Helpers\Helper::formatPrice($order->cash) }}</strong></div>
                <div class="text-center"><strong>Vuelto: {{App\Helpers\Helper::formatPrice($order->cash - $order->total) }}</strong></div>
            @else
                <div class="text-center"><strong>Forma de pago: Tarjeta</strong></div>
                @if($order->status == \App\Models\Order::$STATUS_PENDING)
                    <span class="label label-warning">Pendiente</span>
                    <div class="cobrado">PENDIENTE</div>
                @elseif($order->status == \App\Models\Order::$STATUS_FINISHED)
                    <span class="label label-success">Cobrado</span>
                    <div class="cobrado">COBRADO</div>
                @elseif($order->status == \App\Models\Order::$STATUS_CANCELLED)
                    <span class="label label-danger">Cancelado</span>
                    <div class="cobrado">CANCELADO</div>
                @else
                    <span class="label label-default">N/A</span>
                    <div class="cobrado">N/A</div>
                @endif
            @endif
        </div>
    </div>


@endsection

