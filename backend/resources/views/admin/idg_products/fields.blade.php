<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control',]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('price', 'Precio:') !!}
    <div class="input-group">
        <span class="input-group-addon">$</span>
        {!! Form::text('price', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Categoría:') !!}
    {!! Form::select('category_id', \App\Models\Admin\IDG\Category::getParents(['allowed_to_sell'=>1]), null, ['class' => 'form-control', 'data-toggle'=>'select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::text('order', isset($order) ? $order : null , ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('description', 'Descripción:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<div class="col-sm-6">
    <div class="row">
        <!-- Min Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('min', 'Cant. mínima:') !!}
            {!! Form::text('min', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Max Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('max', 'Cant. máxima:') !!}
            {!! Form::text('max', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div id="multimedia" class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">Multimedia</h4>
            <br/>
            <small class="text-info">Esta opción se utilizar para subir archivos multimedia para que se muestre en la
                sección "Pedidos Grafica"
            </small>
        </div>
        <div class="box-body">
            <div class="row">
                <!-- Min Field -->
                <div class="form-group col-sm-6">
                    @component('admin.idg_products.components.multimedia_image', ['helpBlock'=>'Archivos con formato: JPG,PNG,GIF,AVI,MPG,PDF', 'label'=>'Archivo multimedia:', 'inputName'=>'image_secondary', 'attributes'=>isset($product)? $product->optionsImageSecondary:null])@endcomponent
                    <hr/>
                    @component('admin.idg_products.components.multimedia_file', ['helpBlock'=>'URL externa donde se puede descargar la muestra', 'label'=>'Url del archivo multimedia:', 'inputName'=>'image_secondary', 'attributes'=>isset($product)? $product->optionsImageSecondary:null])@endcomponent
                </div>
                <div class="form-group col-sm-6">
                    @component('admin.idg_products.components.multimedia_image', ['helpBlock'=>'Archivos con formato: JPG,PNG,GIF', 'label'=>'Imagen de presentación:', 'inputName'=>'image_primary', 'attributes'=>isset($product)? $product->optionsImagePrimary:null, 'hidden'=>true])@endcomponent
                </div>
            </div>
        </div>
    </div>
</div>


<div class="form-group col-sm-6">
    {!! Form::hidden('active', 0) !!}
    {!! Form::label('active', 'Activo?:') !!}
    {!! Form::checkbox('active', 1) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('allowed_to_sell', 1) !!}
</div>
<div class="clearfix"></div>
@section('scripts')
    <script type="text/javascript">
        $(function () {
            var fnOption = function (value) {
                if (value === 'file' || !value) {
                    $('input[name=file]').prop('disabled', false)
                    $('input[name=url]').prop('disabled', true)
                    $('#option_file').prop('checked', true)
                } else if (value === 'url') {
                    $('input[name=url]').prop('disabled', false)
                    $('input[name=file]').prop('disabled', true)
                }
            }
            $('input[name=option]').on('change', function (e) {
                fnOption($(this).val())
            });
            fnOption($('input[name=option]:checked').val())
        });
    </script>
@endsection
