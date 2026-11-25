<!-- Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Sector Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sector', 'Sector:') !!}
    {!! Form::text('sector', null, ['class' => 'form-control']) !!}
</div>

<!-- sub_sector Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sub_sector', 'Sub sector:') !!}
    {!! Form::text('sub_sector', null, ['class' => 'form-control']) !!}
</div>
<!-- Province Field -->
<div class="form-group col-sm-6">
    {!! Form::label('province', 'Provincia:') !!}
    {!! Form::text('province', null, ['class' => 'form-control']) !!}
</div>
<!-- Department Field -->
<div class="form-group col-sm-6">
    {!! Form::label('department', 'Partido:') !!}
    {!! Form::text('department', null, ['class' => 'form-control']) !!}
</div>
<!-- City Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city_code', 'Código de ciudad:') !!}
    {!! Form::text('city_code', null, ['class' => 'form-control']) !!}
</div>

<!-- City Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city', 'Ciudad:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- Postal Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('postal_code', 'Código postal:') !!}
    {!! Form::text('postal_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Area Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone_area', 'Area:') !!}
    {!! Form::text('phone_area', null, ['class' => 'form-control']) !!}
</div>
<!-- Phone Area Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone_number', 'Número:') !!}
    {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
</div>
<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.schools.index') !!}" class="btn btn-default">Cancelar</a>
</div>