<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Apellido:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('telephone', 'Teléfono:') !!}
    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('birthday', 'Fec. Nac.:') !!}
    {!! Form::date('birthday', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('role', 'Rol:') !!}
    {!! Form::select('role', App\Admin::getRoles(), null, ['class' => 'form-control']) !!}
</div>
<div class="clearfix"></div>

@if(isset($user))
<div class="form-group col-sm-12">
    {!! Form::label('change_password', 'Cambiar Contraseña?:') !!}
    {!! Form::checkbox('change_password',1,null) !!}
</div>
    @else
    {!! Form::hidden('change_password',1) !!}
@endif
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Contraseña:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('password_confirmation', 'Confirmar contraseña:') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
</div>
<div class="clearfix"></div>
<div class="form-group col-sm-6">
    {!! Form::label('store', 'Tienda:') !!}
    {!! Form::select('store', \App\Models\Store::pluck('name','id'), null, ['class' => 'form-control', 'placeholder'=>'']) !!}
</div>
    <div class="form-group col-sm-12">
        {!! Form::label('is_verified', 'Usuario verificado para navegar?:') !!}
        {!! Form::checkbox('is_verified',1) !!}
        <p class="text-info"><i class="fa fa-info-circle"></i> Si la opcion esta tildada, el usuario podra ingresar a la web sin necesitar activar su cuenta</p>
    </div>
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary btn-gradient']) !!}
    <a href="{!! route('admin.users.index', ['role'=>isset($user) ? $user->role: \App\Admin::$ROLE_ADMIN]) !!}" class="btn btn-default btn-gradient">Volver</a>
</div>
@section('scripts')
    <script type="application/javascript">
        jQuery(function ($) {
            var disabled = function(){
                var disabled = $('input[id=change_password]').prop('checked')
                @if(isset($user))
                $('input[type=password]').prop('disabled', !disabled)
                @endif
                disabled = true
                if ($('select[id=role]').val() === '{!! \App\Admin::$ROLE_STORE !!}') {
                    disabled = false
                }
                $('select[id=store]').prop('disabled', disabled)
                $('select[id=store]').val('')
            }
            $('input[id=change_password], select[id=role]').on('change', function () {
                disabled()
            })
            disabled()
        })
    </script>
@endsection