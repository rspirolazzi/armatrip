<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>
<!-- Descripción Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Descripción:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>
<!-- Descripción Field -->
<div class="form-group col-sm-6">
    {!! Form::label('link', 'Link:') !!}
    {!! Form::text('link', null, ['class' => 'form-control']) !!}
</div>

<div class="col-md-12">
    <div class="box">
        <div class="box-header">Multimedia del paquete</div>
        <div class="box-body">
            {!! Html::inputImage('Logo','logo', isset($brand)?$brand:null, \App\Models\Admin\Brand::$GROUP_LOGO, '') !!}
        </div>
    </div>
</div>

<!-- Orden Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::number('order', isset($order)?$order:1, ['class' => 'form-control']) !!}
</div>

<!-- Activo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Activo:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('active', 0) !!}
        {!! Form::checkbox('active', 1, isset($brand) ? $brand->active : 1) !!}
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.brands.index') !!}" class="btn btn-default">Cancelar</a>
</div>
