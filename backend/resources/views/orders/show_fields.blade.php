<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $order->id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $order->user_id !!}</p>
</div>

<!-- Dirección Id Field -->
<div class="form-group">
    {!! Form::label('address_id', 'Dirección Id:') !!}
    <p>{!! $order->address_id !!}</p>
</div>

<!-- Dirección Flat Field -->
<div class="form-group">
    {!! Form::label('address_flat', 'Dirección Flat:') !!}
    <p>{!! $order->address_flat !!}</p>
</div>

<!-- Items Field -->
<div class="form-group">
    {!! Form::label('items', 'Items:') !!}
    <p>{!! $order->items !!}</p>
</div>

<!-- Payment Field -->
<div class="form-group">
    {!! Form::label('payment', 'Payment:') !!}
    <p>{!! $order->payment !!}</p>
</div>

<!-- Comments Field -->
<div class="form-group">
    {!! Form::label('comments', 'Comments:') !!}
    <p>{!! $order->comments !!}</p>
</div>

<!-- Cash Field -->
<div class="form-group">
    {!! Form::label('cash', 'Cash:') !!}
    <p>{!! $order->cash !!}</p>
</div>

<!-- Sub Total Field -->
<div class="form-group">
    {!! Form::label('sub_total', 'Sub Total:') !!}
    <p>{!! $order->sub_total !!}</p>
</div>

<!-- Delivery Field -->
<div class="form-group">
    {!! Form::label('delivery', 'Delivery:') !!}
    <p>{!! $order->delivery !!}</p>
</div>

<!-- Total Field -->
<div class="form-group">
    {!! Form::label('total', 'Total:') !!}
    <p>{!! $order->total !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $order->status !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $order->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $order->updated_at !!}</p>
</div>

