@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡{{$order->user->name}} realizo una reserva!</h3>
                    <p class="lead">La reserva <b>{{$order->number}}</b>.
                        <!--Hora actualizada de entrega: entre 20:53 y 21:08 hs.-->
                    </p>
                    <!-- Callout Panel -->
                    <!-- Callout Panel -->
                    <p class="callout">
                        Podés comunicarte con ...
                    </p><!-- /Callout Panel -->

                    <!-- order -->
                    <h5 style="margin-bottom: 5px;">{{$order->package->package->name}}</h5>
                    <table class="order" width="100%">
                        <tbody>
                        @foreach($order->products as $item)
                            <tr>
                                <td class="text-center content">-{{$item->product->name}}</td>
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
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection
