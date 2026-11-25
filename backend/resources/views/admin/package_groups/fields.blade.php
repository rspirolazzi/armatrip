<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- Code Field -->
<div class="form-group col-sm-4">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">Colores para distinguir los grupos</div>
        <div class="box-body">
            <div class="form-group col-sm-12">
                {!! Form::label('bg_color', 'Estilos:') !!}
                <div class="row">
                    @foreach (\App\Http\Controllers\Admin\PackageGroupController::$STYLE_OPTIONS as $id=> $style)
                        <div class="col-md-3">
                            <label for="{!! $id !!}" class="alert" style="width:100%;border:{!! $style['color'] !!} solid 1px ;background-color:{!! $style['backgroundColor'] !!};color:{!! $style['color'] !!}">
                                {!! Form::radio('style_value', $id, null, ['id'=>$id]) !!}
                                {!! $style['name']!!}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">Multimedia del paquete</div>
        <div class="box-body">
            {!! Html::inputImage('Imagen de fondo (Desktop)','background_image', isset($packageGroup)?$packageGroup:null, \App\Models\Admin\Package::$GROUP_IMAGE, 'Imagen disponible para la versión Desktop') !!}
            {!! Html::inputImage('Imagen de fondo (Mobile)','background_image_mobile', isset($packageGroup)?$packageGroup:null, \App\Models\Admin\Package::$GROUP_MOBILE, 'Imagen disponible para la versión Mobile') !!}
        </div>
    </div>
</div>
<!-- Active Field -->
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Activo:') !!}
    {!! Form::hidden('active', 0) !!}
    {!! Form::checkbox('active', '1', null) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::text('order', isset($order) ? $order : null , ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.packageGroups.index') !!}" class="btn btn-default">Cancelar</a>
</div>
