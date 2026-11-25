<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'maxlength'=>config('coupons.code_length', 8)]) !!}
    <p class="help-block">Largo maximo {!! config('coupons.code_length', 8) !!}. Ingrese letras y números</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength'=>75]) !!}
    <p class="help-block">Utilice este campo para darle un nombre al cupón.</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('description', 'Descripción:') !!}
    {!! Form::text('description', null, ['class' => 'form-control', 'maxlength'=>255]) !!}
    <p class="help-block">Utilice este campo para darle una descripción al cupón. (Uso interno)</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('reward', 'Descuento:') !!}
    <div class="input-group">
        {!! Form::number('reward', !isset($coupon) ? 1 : $coupon->reward, ['class' => 'form-control']) !!}
        <span class="input-group-addon">%</span>
    </div>
    <p class="help-block">Porcentaje del descuento a aplicar. Como minimo 0, como maximo 100</p>
</div>

<div class="clearfix"></div>
<div class="panel panel-default">
    <h4 class="panel-heading">Vigencia y vencimiento</h4>
    <div class="panel-body">
        <div class="form-group col-sm-6">
            {!! Form::label('start_at', 'Vigencia:') !!}
            {!! Form::date('start_at', !isset($coupon) ? Carbon\Carbon::now() : $coupon->start_at, ['class' => 'form-control', 'data-date-format']) !!}
            <p class="help-block">Ingrese la fecha en formato YYYY-MM-DD</p>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('end_at', 'Vencimiento:') !!}
            {!! Form::date('end_at', !isset($coupon) ? Carbon\Carbon::now()->addDays(config('coupons.default_expiration_days')):$coupon->end_at, ['class' => 'form-control', 'data-date-format']) !!}
            <p class="help-block">Ingrese la fecha en formato YYYY-MM-DD</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-sm-6">
    {!! Form::label('enable_range', 'Habilitar cupones validos entre rango de horas?:')	!!}
    {!! Form::checkbox('enable_range', 1,  null, ['id' => 'enable_range']) !!}
</div>
<div class="clearfix"></div>
<div class="panel panel-default">
    <h4 class="panel-heading">Rango de horas</h4>
    <div class="panel-body">
        <div class="form-group col-sm-6">
            {!! Form::label('hs_start_at', 'Desde:') !!}
            {!! Form::select('hs_start_at', \App\Http\Controllers\Admin\CouponController::getDefaultsHS() , null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('hs_end_at', 'Hasta:') !!}
            {!! Form::select('hs_end_at', \App\Http\Controllers\Admin\CouponController::getDefaultsHS() , null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::hidden('enabled', 0) !!}
    {!! Form::label('enabled', 'Habilitado:')	!!}
    {!! Form::checkbox('enabled', 1,  (!isset($coupon) ? 1 : null), ['id' => 'enabled']) !!}
    <p class="help-block">Determina si el cupón puede utilizarse o no.</p>
</div>

<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    @can('admin.coupons.index')<a href="{!! route('admin.coupons.index') !!}" class="btn btn-default">Cancel</a>@endcan
</div>
@section('scripts')
    <script type="application/javascript">
        $(function () {
            var enabledRangeTime = function (enabled) {
                $('select[name=hs_start_at], select[name=hs_end_at]').prop('disabled', !enabled)
            }
            $('input[name=enable_range]').on('change', function (e) {
                enabledRangeTime($(this).prop('checked'))
            });
            var checked = {!! !isset($coupon) ? 'false' : !empty($coupon->hs_start_at)||!empty($coupon->hs_end_at) ? 'true':'false' !!}
            enabledRangeTime(checked)
            $('input[name=enable_range]').prop('checked', checked)
        })
    </script>
@endsection