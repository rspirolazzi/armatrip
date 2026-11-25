<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Start At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_at', 'Start At:') !!}
    {!! Form::date('start_at', null, ['class' => 'form-control']) !!}
</div>

<!-- End At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_at', 'End At:') !!}
    {!! Form::date('end_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Active Field -->
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Active:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('active', false) !!}
        {!! Form::checkbox('active', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.dateRanges.index') !!}" class="btn btn-default">Cancelar</a>
</div>
