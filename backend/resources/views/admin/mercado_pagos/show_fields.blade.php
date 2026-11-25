<!-- Id Field -->
<div class="col-md-6">
    {!! Form::label('id', 'Código:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->id !!}</p>
</div>

<div class="col-md-6">
    {!! Form::label('order_id', 'Orden:') !!}
    <p class="form-control form-control-static">{!! App\Helpers\Helper::humanNameModel($mercadoPago->order_type) !!} -> {!! $mercadoPago->order_id !!}</p>
</div>
<div class="col-md-6">
    {!! Form::label('transaction_amount', 'Total:') !!}
    <p class="form-control form-control-static">{!! App\Helpers\Helper::formatPrice($mercadoPago->transaction_amount) !!}</p>
</div>
<div class="col-md-6">
    {!! Form::label('token', 'Token:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->token !!}</p>
</div>
<div class="col-md-6">
    {!! Form::label('description', 'Descripcion:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->description !!}</p>
</div>

<div class="col-md-6">
    {!! Form::label('payment_method_id', 'Tipo:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->payment_method_id !!}</p>
</div>
<div class="col-md-6">
    {!! Form::label('status', 'Estado:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->status !!}</p>
</div>
<div class="col-md-12">
    {!! Form::label('body', 'Respuesta:') !!}
    <a href="#body" data-toggle="collapse">Ver mas</a>
    <div id="body" class="collapse">
        <table class="table table-striped">
            @if(isset($mercadoPago->bodyToArray['response']))
                @foreach($mercadoPago->bodyToArray['response'] as $key => $value)
                    <tr>
                        <th>{!! $key !!}</th>
                        <td>
                        @if(is_array($value))
                            {!! print_r($value) !!}
                        @else
                            {!! $value !!}
                        @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</div>
<div class="clearfix"></div>

<!-- Created At Field -->
<div class="col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control form-control-static">{!! $mercadoPago->updated_at !!}</p>
</div>

<div class="clearfix"></div>
