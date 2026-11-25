@extends('layouts.app')
@section('content')
    <section class="content-header">
        <h1>
            Reserva N° <b>{{$order->number}}</b>
        </h1>
    </section>
    @include('adminlte-templates::common.errors')
    @include('flash::message')
    <div class="content">
        <div class="box box-primary">
            <div class="box-body" style="margin: 0 20px">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <div class="box-title">Cambiar estado y/o hacer un comentario</b></b>:</div>
                            </div>
                            <div class="box-body">
                                {!! Form::model($order, ['route' => ['admin.orders.update', $order->id], 'method' => 'patch']) !!}
                                <div class="form-group col-sm-6">
                                    {!! Form::label('status', 'Estado:') !!}
                                    {!! Form::select('status', [
                                        \App\Models\Order::$STATUS_PENDING => 'Pendiente',
                                        \App\Models\Order::$STATUS_CANCELLED => 'Cancelado',
                                        \App\Models\Order::$STATUS_FINISHED => 'Finalizado',
                                        \App\Models\Order::$STATUS_SOLD => 'Vendido',
                                        \App\Models\Order::$STATUS_CONTACTED=> 'Contactado',
                                    ], null , ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-12">
                                    {!! Form::label('comments', 'Comentario:') !!}
                                    {!! Form::textarea('comments', null , ['class' => 'form-control']) !!}
                                </div>
                                <div class="clearfix"></div>
                                {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
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
                                                <p class="form-control-static"> {{ $order->school_name }}</p>
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
                                                <p class="form-control-static"> {!! isset($order->reserved_at) ? $order->reserved_at->format('d/m/Y H:i') :  $order->updated_at->format('d/m/Y H:i') !!}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Fecha de viaje: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{$order->dateToTravel()->format('d/m/Y')}}</p>
                                            </div>
                                        </div>
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
                                                <p class="form-control-static">{{$order->package->installment }} ({{$order->package->tax}}%)</p>
                                            </div>
                                        </div>
                                        @if($order->package->findDateRange()!==null)
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Recargo / Descuento: </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{$order->package->findDateRange()->tax}}%</p>
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
                                                <td><b>{{ isset($product->product) && isset($product->product->category) ? $product->product->category->name :'N/A'}}</b></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

