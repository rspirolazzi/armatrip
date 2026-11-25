<div class="col-sm-4">
    <!-- Id Field -->
    <div class="form-group">
        {!! Form::label('id', 'Código:') !!}
        <p class="form-control form-control-static">{!! $user->id !!}</p>
    </div>
</div>

<div class="col-sm-4">
    <!-- Nombre Field -->
    <div class="form-group">
        {!! Form::label('name', 'Nombre:') !!}
        <p class="form-control form-control-static">{!! $user->name !!}</p>
    </div>
</div>

<div class="col-sm-4">
    <!-- Teléfono Field -->
    <div class="form-group">
        {!! Form::label('telephone', 'Teléfono:') !!}
        <p class="form-control form-control-static">{!! $user->phone_code !!} - {!!$user->phone_number!!}</p>
    </div>
</div>

<div class="col-sm-4">
    <!-- Email Field -->
    <div class="form-group">
        {!! Form::label('email', 'Email:') !!}
        <p class="form-control form-control-static">{!! $user->email !!}</p>
    </div>
</div>


<div class="col-sm-4">
    <!-- Created At Field -->
    <div class="form-group">
        {!! Form::label('created_at', 'Created At:') !!}
        <p class="form-control form-control-static">{!! $user->created_at !!}</p>
    </div>
</div>

<div class="col-sm-4">
    <!-- Updated At Field -->
    <div class="form-group">
        {!! Form::label('updated_at', 'Updated At:') !!}
        <p class="form-control form-control-static">{!! $user->updated_at !!}</p>
    </div>
</div>

<div class="col-sm-12">
    <div class="box box-danger">
        <div class="box-header with-border">
            <div class="box-title">Reservas realizadas</b></b>:</div>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <tr>
                    <th>Nro</th>
                    <th>Fecha</th>
                    <th>Empresa</th>
                    <th>Importe</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                <tbody>
                @if($user->orders)
                @foreach($user->orders as $order)
                    <tr>
                    <td class="">{!! $order->number !!}</td>
                    <td class="">{!! isset($order->reserved_at) ? $order->reserved_at->format('d/m/Y H:i') :  $order->updated_at->format('d/m/Y H:i') !!}</td>
                    <td class="">{!! $order->store->name !!}</td>
                    <td class="">{!! App\Helpers\Helper::formatPrice($order->total) !!}</td>
                    <td class="text-center" >
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
                    <td class="text-center">
                        <div class='btn-group'>
                            @can('admin.orders.show')
                            <a href="{!! route('admin.orders.show', [$order->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-eye"></i></a>
                            @endcan
                        </div>
                    </td>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>