<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Código:') !!}
    <p class="form-control form-control-static">{!! $contact->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $contact->name !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('phone', 'Celular:') !!}
    <p class="form-control form-control-static">{!! $contact->phone !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Empresa:') !!}
    <p class="form-control form-control-static">{!! $contact->store_code !!}</p>
</div>

<!-- Comment Field -->
<div class="form-group">
    {!! Form::label('comment', 'Comentario:') !!}
    <p class="form-control form-control-static">{!! $contact->comment !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Fecha:') !!}
    <p class="form-control form-control-static">{!! $contact->created_at->format('d-m-Y H:i') !!}</p>
</div>