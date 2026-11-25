@filter(['route'=>'admin.orders.index', 'route_export'=>'admin.orders.export'])
    <!--<div class="col-md-3">
        {!! Form::date('created_at',null , Request::get('created_at'), ['class' => 'form-control', 'placeholder'=>'Reserva']) !!}
    </div>
    <div class="col-md-3">
        {!! Form::date('created_at',null , Request::get('created_at'), ['class' => 'form-control', 'placeholder'=>'Reserva']) !!}
    </div>-->
    <div class="col-md-5">
        {!! Form::select('store',\App\Models\Store::pluck('name', 'id') , Request::get('store'), ['class' => 'form-control', 'placeholder'=>'Empresas']) !!}
    </div>
    <div class="col-md-5">
        {!! Form::select('status', [
            \App\Models\Order::$STATUS_PENDING => 'Pendiente',
            \App\Models\Order::$STATUS_CANCELLED => 'Cancelado',
            \App\Models\Order::$STATUS_FINISHED => 'Finalizado',
            \App\Models\Order::$STATUS_SOLD=> 'Vendido',
            \App\Models\Order::$STATUS_CONTACTED=> 'Contactado',
        ], Request::get('status'), ['class' => 'form-control', 'placeholder'=>'Estado']) !!}
    </div>
@endfilter                
<hr/>
