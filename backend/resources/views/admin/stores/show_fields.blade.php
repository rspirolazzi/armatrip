<div class="form-group col-sm-12">
    {!! Form::label('name', 'Código:') !!}
    <p class="form-control form-control-static">{!! $store->id !!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $store->name !!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('telephone', 'Teléfono:') !!}
    <p class="form-control form-control-static">{!! $store->telephone !!}</p>
</div>
<div class="form-group col-sm-12 col-lg-6">
    {!! Form::label('address', 'Dirección:') !!}
    <p class="form-control form-control-static">{!! $store->address !!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    <p class="form-control form-control-static">{!! $store->email !!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Activo:') !!}
    <p class="form-control form-control-static">{!! $store->active ? 'SI':'NO' !!}</p>
</div>
<div class="form-group col-sm-12">
    <div class="panel panel-default">
        <h5 class="panel-heading">Configuracion de localizacion y delivery</h5>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-6">
                    {!! Form::label('lat', 'Latitud:') !!}
                    <p class="form-control form-control-static">{!! $store->lat !!}</p>
                </div>

                <div class="form-group col-sm-6">
                    {!! Form::label('lon', 'Longitud:') !!}
                    <p class="form-control form-control-static">{!! $store->lon !!}</p>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('map_distance', 'Distancia de delivery (en KM):') !!}
                    <p class="form-control form-control-static">{!! $store->map_distance!!}</p>

                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('delivery', 'Precio del Delivery:') !!}
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <p class="form-control form-control-static">{!! $store->delivery!!}</p>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('min_amount', 'Monto para realizar pedido:') !!}
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <p class="form-control form-control-static">{!! $store->min_amount!!}</p>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('map_zoom', 'Zoom del mapa:') !!}
                    <p class="form-control form-control-static">{!! $store->map_zoom!!}</p>
                </div>
            </div>
        </div>
    </div>
</div>