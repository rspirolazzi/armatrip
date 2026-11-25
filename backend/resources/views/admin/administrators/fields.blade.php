<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('role', 'Rol:') !!}
    {!! Form::select('role', \Spatie\Permission\Models\Role::all()->pluck('name', 'name'), isset($user) ?$user->getRoleNames()->first():null, ['class' => 'form-control']) !!}
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
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary btn-gradient']) !!}
    <a href="{!! route('admin.administrators.index', ['role'=>isset($user) ? $user->role: \App\Admin::$ROLE_ADMIN]) !!}" class="btn btn-default btn-gradient">Volver</a>
</div>
@section('scripts')
    <script type="application/javascript">
        jQuery(function ($) {
            var disabled = function(){
                var disabled = $('input[id=change_password]').prop('checked')
                @if(isset($user))
                $('input[type=password]').prop('disabled', !disabled)
                @endif
            }
            $('input[id=change_password], select[id=role]').on('change', function () {
                disabled()
            })
            disabled()
        })
    </script>
@endsection