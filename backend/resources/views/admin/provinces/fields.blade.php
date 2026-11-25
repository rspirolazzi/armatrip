<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code', 'maxlength'=>20]) !!}
</div>
<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_id', 'Padre:') !!}
    {!! Form::select('parent_id', \App\Models\Admin\Province::pluck('name', 'id')->sortBy('name'), null, ['class' => 'form-control']) !!}
</div>

<!-- Tax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tax', 'Recargo:') !!}
    {!! Form::number('tax', null, ['class' => 'form-control']) !!}
</div>

<!-- Active Field -->
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Activo?:') !!}
    {!! Form::hidden('active', 0) !!}
    {!! Form::checkbox('active', '1', null) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.provinces.index') !!}" class="btn btn-default">Cancelar</a>
</div>
