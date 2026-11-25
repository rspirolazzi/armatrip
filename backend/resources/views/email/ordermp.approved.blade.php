@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡Gracias, {{$order->user->name}}!</h3>
                    <p class="lead">Tu pedido Nro <b>{{$order->number}}</b> a {{$order->store->name}} fue aprobado.
                        <!--Hora actualizada de entrega: entre 20:53 y 21:08 hs.-->
                    </p>
                    <!-- Callout Panel -->
                    <p class="callout">
                        Podés comunicarte con {{$order->store->name}} al {{$order->store->telephone}}.
                    </p><!-- /Callout Panel -->

                    <!-- order -->
                    <h5 style="margin-bottom: 5px;">Tu pedido</h5>
                    <table class="order" width="100%">
                      <thead>
                        <tr>
                          <th>Producto</th>
                          <th>Cantidad</th>
                          <th>Sabores</th>
                          <th>Precio/unidad</th>
                        </tr>
                      </thead>
                        <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="text-center content">{{$item->product->name}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-center">{{$item->items}}</td>
                                <td class="text-center">${{$item->price}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-center"><span class="badge">Total:</span></td>
                            <td class="text-center"><b>${{$order->total}}</b></td>
                        </tr>
                      </tbody>
                    </table><!-- /order -->
                    <h5 style="padding-top: 15px; margin-bottom: 5px;">Dirección de entrega</h5>
                    <table class="order" width="100%">
                        <tr>
                            <td>{{$order->address->street}} {{$order->address->number}} (Entre calles:{{$order->address->line1}}, Piso/Depto:{{$order->address->line2}}) - {{$order->address->city}} {{$order->address->state}}</td>
                        </tr>
                    </table><!-- /order -->
                    <h5 style="padding-top: 15px; margin-bottom: 5px;">Tiempo de entrega</h5>
                    <table class="order" width="100%">
                        <tr>
                            <td>{{$order->delivery_time}}</td>
                        </tr>
                    </table><!-- /order -->
                    <h5 style="padding-top: 15px; margin-bottom: 5px;">Medio de pago</h5>
                    <table class="order" width="100%">
                        <tr>
                            <td>
                                @if ($order->payment == \App\Models\Order::$PAYMENT_CARD)
                                    Tarjeta
                                @elseif ($order->payment == \App\Models\Order::$PAYMENT_CASH)
                                    Efectivo
                                @endif
                            </td>
                        </tr>
                    </table><!-- /order -->
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection
