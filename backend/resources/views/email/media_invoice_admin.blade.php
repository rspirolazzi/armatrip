@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡Has recibido un pedido de la sucursal, {{$invoice->store->name}}!</h3>
                    <p class="lead">El nro de pedido es <b>{{$invoice->order_number}}</b></p>
                    <!-- Callout Panel -->
                    <p class="callout">
                        Se adjunto en este email un PDF y Excel
                    </p><!-- /Callout Panel -->

                    <!-- order -->
                    <h5 style="margin-bottom: 5px;">Detalle del pedido</h5>
                    <table class="order" width="100%">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoice->items as $index => $item)
                            <tr>
                                <td class="text-center content">{!! $item->model->name !!}</td>
                                <td class="text-center">
                                    {!! $item->model->family->name !!}
                                    @if($item->model->subFamily)
                                        -> {!! $item->model->subFamily->name  !!}
                                    @endif
                                </td>
                                <td class="text-center">{!! $item->qty !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table><!-- /order -->
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection
