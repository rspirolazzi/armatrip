<!-- Id Field -->
<div class="col-md-12 form-group">
    {!! Form::label('id', '#:') !!}
    <p class="form-control form-control-static">{!! $province->id !!}</p>
</div>

<!-- Name Field -->
<div class="col-md-6 form-group">
    {!! Form::label('code', 'Código:') !!}
    <p class="form-control form-control-static">{!! $province->code !!}</p>
</div>
<!-- Name Field -->
<div class="col-md-6 form-group">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $province->name !!}</p>
</div>

<!-- Tax Field -->
<div class="col-md-6 form-group">
    {!! Form::label('tax', 'Recargo:') !!}
    <p class="form-control form-control-static">{!! $province->tax !!}</p>
</div>

<!-- Active Field -->
<div class="col-md-6 form-group">
    {!! Form::label('active', 'Activo?:') !!}
    <p class="form-control form-control-static">{!! $province->active !!}</p>
</div>

<!-- Updated At Field -->
<div class="col-md-6 form-group">
    {!! Form::label('updated_at', 'Ultima actualización:') !!}
    <p class="form-control form-control-static">{!! $province->updated_at !!}</p>
</div>

