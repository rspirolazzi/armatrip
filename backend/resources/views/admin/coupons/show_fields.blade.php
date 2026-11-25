<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $coupon->id !!}</p>
</div>

<!-- Código Field -->
<div class="form-group">
    {!! Form::label('code', 'Código:') !!}
    <p>{!! $coupon->code !!}</p>
</div>

<!-- Nombre Field -->
<div class="form-group">
    {!! Form::label('name', 'Nombre:') !!}
    <p>{!! $coupon->name !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $coupon->description !!}</p>
</div>

<!-- Descuento Field -->
<div class="form-group">
    {!! Form::label('reward', 'Descuento:') !!}
    <p>{!! $coupon->reward !!}</p>
</div>

<!-- Is Disposable Field -->
<div class="form-group">
    {!! Form::label('is_disposable', 'Is Disposable:') !!}
    <p>{!! $coupon->is_disposable !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $coupon->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $coupon->updated_at !!}</p>
</div>

