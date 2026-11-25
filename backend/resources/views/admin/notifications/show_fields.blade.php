<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control form-control-static">{!! $notification->id !!}</p>
</div>

<!-- Código Field -->
<div class="form-group col-md-6">
    {!! Form::label('code', 'Código:') !!}
    <p class="form-control form-control-static">{!! $notification->code !!}</p>
</div>

<!-- Titulo Field -->
<div class="form-group col-md-6">
    {!! Form::label('title', 'Titulo:') !!}
    <p class="form-control form-control-static">{!! $notification->title !!}</p>
</div>

<!-- Mensaje Field -->
<div class="form-group col-md-6">
    {!! Form::label('message', 'Mensaje:') !!}
    <p class="form-control form-control-static">{!! $notification->message !!}</p>
</div>

<!-- Duración Field -->
<div class="form-group col-md-6">
    {!! Form::label('duration', 'Duración:') !!}
    <p class="form-control form-control-static">{!! $notification->duration !!}</p>
</div>

<!-- Type Field -->
<div class="form-group col-md-6">
    {!! Form::label('type', 'Tipo:') !!}
    <p class="form-control form-control-static">{!! $notification->type !!}</p>
</div>

<!-- Orden Field -->
<div class="form-group col-md-6">
    {!! Form::label('order', 'Orden:') !!}
    <p class="form-control form-control-static">{!! $notification->order !!}</p>
</div>

<!-- Uri Field -->
<div class="form-group col-md-6">
    {!! Form::label('uri', 'Uri:') !!}
    <p class="form-control form-control-static">{!! $notification->uri !!}</p>
</div>

<!-- Rules Field -->
<div class="form-group col-md-12">
    {!! Form::label('rules', 'Regla:') !!}
    <p class="form-control form-control-static">{!! $notification->rules !!}</p>
</div>
<div class="clearfix"></div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Creado:') !!}
    <p class="form-control form-control-static">{!! $notification->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Actualizado:') !!}
    <p class="form-control form-control-static">{!! $notification->updated_at !!}</p>
</div>

<div class="clearfix"></div>
