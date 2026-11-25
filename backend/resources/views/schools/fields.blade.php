<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('year', 'Year:') !!}
    {!! Form::number('year', null, ['class' => 'form-control']) !!}
</div>

<!-- Division Field -->
<div class="form-group col-sm-6">
    {!! Form::label('division', 'Division:') !!}
    {!! Form::text('division', null, ['class' => 'form-control']) !!}
</div>

<!-- Turn Field -->
<div class="form-group col-sm-6">
    {!! Form::label('turn', 'Turn:') !!}
    {!! Form::text('turn', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('schools.index') !!}" class="btn btn-default">Cancel</a>
</div>
