@extends('layouts.app')
@section('scripts')
    <script type="application/javascript">
        $(function () {
            $(".print").on('click', function () {
                var height = (($("#print").outerHeight() * 0.029) + 5) + "cm";
                var width = "8cm";
                var divToPrint = document.getElementById('print');
                var newWin = window.open('', 'Print-Window');

                newWin.document.open();

                newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><link rel="stylesheet" href="{!! env('APP_URL_FIX_IMAGES') !!}/css/comanda.css"><style>#print{display:block} @media print {@page{size: ' + width + ' ' + height + ';margin:0;}}</style><body onload="window.print()">' + divToPrint.innerHTML + '</body>');

                newWin.document.close();

                setTimeout(function () {
                    newWin.close();
                }, 10);
            })
        })
    </script>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Reserva N° <b>{{$order->number}}</b>
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body" style="margin: 0 20px">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <div class="box-title">Detalles del usuario:</div>
                            </div>
                            <div class="box-body">
                                <div class="col-sm-6">
                                    <div class="form-horizontal">
                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Nombre: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->user->name }} {{ $order->user->last_name }}</p>
                                            </div>
                                        </div>

                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Telefono: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->user->phone_code }} {{ $order->user->phone_number }}</p>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Email: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->user->email }}</p>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Escuela: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">
                                                    {!! $order->schoolNameComplete !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-horizontal">
                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Provincia: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->province->name }}</p>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Pasajeros: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->package->qty }}</p>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <label class="col-sm-4 control-label">Empresa: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->store->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <div class="box-title">Detalle de la reserva:</div>
                            </div>
                            <div class="box-body">
                                <div class="col-sm-6">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nro: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static"> {{ $order->number}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Fecha de compra: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{!! isset($order->reserved_at) ? $order->reserved_at->format('d/m/Y H:i') :  $order->updated_at->format('d/m/Y H:i') !!}</p>
                                            </div>
                                        </div>
                                        @if($order->store->code==$turismo_individual)
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Fecha de viaje:
                                                    (desde/hasta) </label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-static">{{$order->date_start->format('d/m/Y')}}
                                                        > {{$order->date_end->format('d/m/Y')}}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Fecha de viaje: </label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-static">{{$order->dateToTravel()->format('d/m/Y')}}</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Estado: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">
                                                    @if($order->status == \App\Models\Order::$STATUS_PENDING)
                                                        <span class="label label-warning">Pendiente</span>
                                                    @elseif($order->status == \App\Models\Order::$STATUS_FINISHED)
                                                        <span class="label label-success">Finalizado</span>
                                                    @elseif($order->status == \App\Models\Order::$STATUS_CANCELLED)
                                                        <span class="label label-danger">Cancelado</span>
                                                    @else
                                                        <span class="label label-default">N/A</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Por pasajero: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{App\Helpers\Helper::formatPrice($order->sub_total) }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cuotas: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{$order->package->installment }}
                                                    ({{$order->package->tax}}%)</p>
                                            </div>
                                        </div>
                                        @if($order->package->findDateRange()!==null)
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Recargo / Descuento: </label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-static">{{$order->package->findDateRange()->tax}}
                                                        %</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Total: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{App\Helpers\Helper::formatPrice($order->total) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($order->coupon)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <div class="box-title">Cupón aplicado:</div>
                                </div>
                                <div class="box-body">
                                    <table class="table">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descuento (%)</th>
                                            <th>Valido</th>
                                            <th></th>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>{{ $order->coupon->name }}</td>
                                            <td>{{ $order->coupon->reward }}</td>
                                            <td>{{ $order->coupon->start_at->format('d/m/y H:i') }}
                                                -> {{ $order->coupon->end_at->format('d/m/y H:i') }}</td>
                                            <td><a href="{{ route('admin.coupons.show', $order->coupon->id) }}"><i
                                                            class="fa fa-eye"></i></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($order->mercadoPago)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <div class="box-title">Mercado Pago</b></b>:</div>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Nro</th>
                                            <th>Importe</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td>{{ $order->mercadoPago->created_at->format('d/m/y H:i:s')}}</td>
                                            <td>
                                                <b>{{ isset($order->mercadoPago->bodyToArray['response']) ? $order->mercadoPago->bodyToArray['response']['id'] :'' }}</b>
                                            </td>
                                            <td>
                                                <b> {{ App\Helpers\Helper::formatPrice($order->mercadoPago->transaction_amount) }}</b>
                                            </td>
                                            <td><b> {{ $order->mercadoPago->status }}</b></td>
                                            <td>
                                                <a title="Ver detalles del pago" alt="Ver detalles del pago"
                                                   href="{{route('admin.mercadoPagos.show', $order->mercadoPago->id)}}"><i
                                                            class="fa fa-eye"></i></a>
                                                <a title="Ver historiales de pagos" alt="Ver historiales de pagos"
                                                   href="{{route('admin.mercadoPagos.index', ['search'=>$order->id])}}"><i
                                                            class="fa fa-history"></i></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <div class="box-title">Paquete <b>{{$order->package->name}}</b></b>:</div>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                    </tr>
                                    @foreach($order->products as $index => $product)
                                        <tr>
                                            <td>{{ isset($product->product) ? $product->product->name :'N/A'}}</td>
                                            <td>
                                                <b>{{ isset($product->product) && isset($product->product->category) ? $product->product->category->name :'N/A'}}</b>
                                            </td>
                                            <td><b> {{  App\Helpers\Helper::formatPrice($product->total) }}</b></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{!! route('admin.orders.index') !!}" class="btn btn-default btn-gris"> Volver</a>
                        {{--<a href="{!! route('admin.orders.index') !!}" class="btn btn-default btn-verde print">
                            IMPRIMIR</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

