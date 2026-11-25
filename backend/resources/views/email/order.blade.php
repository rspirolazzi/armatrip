@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3 class="hello">¡Hola, {{$order->user->name}}!</h3>
                    <h3>Gracias por elegirnos!! Te enviamos la cotización que armaste.</h3>

                    @if($order->school_name)
                        <p style="margin-bottom: 0">Colegio: {{$order->school_name}}</p>
                    @endif
                    <p style="margin-bottom: 0">Fecha del viaje: {{$order->biweekly}} quincena de {{$order->month}} de {{$order->year}}</p>
                    <p style="margin-bottom: 0">Cantidad de pasajeros: {{$order->package->qty}}</p>
                    <h3 style="margin-top: 15px">{{$order->package->package->name}}</h3>

                    <ul class="list-categories">
                        @foreach($products as $category)
                            <li class="list-category">
                                {{$category[0]['name']}}
                                <ul class="list-products">
                                    @foreach($category as $product)
                                        <li class="list-product">{{$product['product']['name']}}</li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>

                    <div class="installment">{{$order->package->installment}} cuotas de {{ App\Helpers\Helper::formatPrice($order->sub_total/$order->package->installment)}}</div>
                    <div class="price" style="margin-bottom: 20px">Precio individual del pack: {{ App\Helpers\Helper::formatPrice($order->sub_total)}}</div>
                    @if($order->store->is_foreign && is_array($order->store->options))
                    <div class="price" style="margin-bottom: 20px">{!! $order->store->options['currency_message'] !!}</div>
                    @endif
                    @if($order->coupon)
                    <hr/>
                        <p style="margin-bottom: 0">T&uacute; cup&oacute;n: <b>{{$order->coupon->name}}</b> se aplic&oacute; con &eacute;xito</p>
                    @endif
                    @if($order->mercadopago)
                    <hr/>
                    <div class="price" style="margin-bottom: 10px">T&uacute; reserva por: {{App\Helpers\Helper::formatPrice($order->mercadoPago->transaction_amount)}}, 
                    
                        @if($order->status == \App\Models\Order::$STATUS_PENDING)
                            <span style="font-weight:bold">esta pendiente de confirmación</span>
                        @elseif($order->status == \App\Models\Order::$STATUS_FINISHED)
                            <span style="font-weight:bold">fue aprobada con &eacute;xito</span>
                        @elseif($order->status == \App\Models\Order::$STATUS_CANCELLED)
                            <span style="font-weight:bold">fue cancelad&oacute;</span>
                        @else
                            <span style="font-weight:bold">tuvo un error</span>
                        @endif
                    </div>
                    @endif
                    <hr/>
                    <pre style="margin-top: 20px">{!! $order->package->package->description!!}</pre>

                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection
