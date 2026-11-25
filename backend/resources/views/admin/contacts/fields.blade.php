<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Celular:') !!}
    {!! Form::phone('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary btn-gradient']) !!}
    <a href="{!! route('admin.contacts.index') !!}" class="btn btn-default btn-gradient">Volver</a>
</div>
