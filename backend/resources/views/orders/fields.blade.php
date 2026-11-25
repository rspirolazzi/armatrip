<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::select('user_id', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Dirección Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_id', 'Dirección Id:') !!}
    {!! Form::select('address_id', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Dirección Flat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_flat', 'Dirección Flat:') !!}
    {!! Form::text('address_flat', null, ['class' => 'form-control']) !!}
</div>

<!-- Items Field -->
<div class="form-group col-sm-6">
    {!! Form::label('items', 'Items:') !!}
    {!! Form::text('items', null, ['class' => 'form-control']) !!}
</div>

<!-- Payment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment', 'Payment:') !!}
    {!! Form::select('payment', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Comments Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('comments', 'Comments:') !!}
    {!! Form::textarea('comments', null, ['class' => 'form-control']) !!}
</div>

<!-- Cash Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cash', 'Cash:') !!}
    {!! Form::text('cash', null, ['class' => 'form-control']) !!}
</div>

<!-- Sub Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sub_total', 'Sub Total:') !!}
    {!! Form::text('sub_total', null, ['class' => 'form-control']) !!}
</div>

<!-- Delivery Field -->
<div class="form-group col-sm-6">
    {!! Form::label('delivery', 'Delivery:') !!}
    {!! Form::text('delivery', null, ['class' => 'form-control']) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total', 'Total:') !!}
    {!! Form::text('total', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('orders.index') !!}" class="btn btn-default">Cancelar</a>
</div>
