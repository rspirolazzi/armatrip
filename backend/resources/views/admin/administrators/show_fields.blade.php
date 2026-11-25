<div class="col-sm-12">
    <!-- Id Field -->
    <div class="form-group">
        {!! Form::label('id', 'Código:') !!}
        <p class="form-control form-control-static">{!! $user->id !!}</p>
    </div>
</div>

<div class="col-sm-12">
    <!-- Nombre Field -->
    <div class="form-group">
        {!! Form::label('name', 'Nombre:') !!}
        <p class="form-control form-control-static">{!! $user->name !!}</p>
    </div>
</div>

<div class="col-sm-12">
    <!-- Apellido Field -->
    <div class="form-group">
        {!! Form::label('last_name', 'Apellido:') !!}
        <p class="form-control form-control-static">{!! $user->last_name !!}</p>
    </div>
</div>

<div class="col-sm-12">
    <!-- Teléfono Field -->
    <div class="form-group">
        {!! Form::label('telephone', 'Teléfono:') !!}
        <p class="form-control form-control-static">{!! $user->telephone !!}</p>
    </div>
</div>


<div class="col-sm-12">
    <!-- Fec. Nac. Field -->
    <div class="form-group">
        {!! Form::label('birthday', 'Fec. Nac.:') !!}
        <p class="form-control form-control-static">{!! $user->birthday !!}</p>
    </div>
</div>


<div class="col-sm-12">
    <!-- Email Field -->
    <div class="form-group">
        {!! Form::label('email', 'Email:') !!}
        <p class="form-control form-control-static">{!! $user->email !!}</p>
    </div>
</div>


<div class="col-sm-12">
    <!-- Created At Field -->
    <div class="form-group">
        {!! Form::label('created_at', 'Created At:') !!}
        <p class="form-control form-control-static">{!! $user->created_at !!}</p>
    </div>
</div>

<div class="col-sm-12">
    <!-- Updated At Field -->
    <div class="form-group">
        {!! Form::label('updated_at', 'Updated At:') !!}
        <p class="form-control form-control-static">{!! $user->updated_at !!}</p>
    </div>
</div>

