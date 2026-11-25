<div class="form-group col-sm-8">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', ]) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>
<div class="clearfix"></div>

<div class="form-group col-sm-4">
    {!! Form::label('package_group_id', 'Agrupar por:') !!}
    {!! Form::select('package_group_id', \App\Models\Admin\PackageGroup::pluck('name', 'id'), null, ['class' => 'form-control', 'data-toggle'=>'select2','placeholder'=>'Seleccionar',  ]) !!}
</div>
<div class="form-group col-sm-8">
    {!! Form::label('tags', 'Tags:') !!}
    {!! Form::text('tags', null, ['class' => 'form-control', ]) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('description', 'Descripción:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', ]) !!}
</div>
<div class="form-group col-sm-12">
    {!! Form::label('more_info', 'Más información:') !!}
    {!! Form::textarea('more_info', null, ['class' => 'form-control', ]) !!}
    <p class="help-block">Una vez cargada, esta información se visualizara en la App de Reservas con el icono <i class="fa fa-info"></i></p>
</div>
{{--
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Imagen:') !!}
    {!! Form::text('image', null, ['class' => 'form-control', ]) !!}
</div>--}}
<div class="form-group col-sm-6">
    {!! Form::label('store_id', 'Empresa:') !!}
    {!! Form::select('store_id', \App\Models\Store::pluck('name', 'id'), null, ['class' => 'form-control', 'data-toggle'=>'select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('price', 'Precio:') !!}
    <div class="input-group">
        <span class="input-group-addon">$</span>
        {!! Form::number('price', null, ['class' => 'form-control']) !!}
    </div>
    <p class="text-muted">Precio base del paquete por persona</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('increment_percent', 'Incremento:') !!}
    <div class="input-group">
        {!! Form::number('increment_percent', isset($package) ? $package->increment_percent:0, ['class' => 'form-control']) !!}
        <span class="input-group-addon">%</span>
    </div>
    <p class="text-muted">Incremento al precio base que se aplicara</p>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">Configurar cuotas e intereses del paquete</div>
        <div class="box-body">
            <div class="form-group col-sm-6">
                {!! Form::label('installment', 'Cuotas:') !!}
                {!! Form::select('installment', [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20,21=>21,22=>22,23=>23,24=>24], null, ['class' => 'form-control', 'data-toggle'=>'select2']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('tax', 'Tasa de interes (%) por cuota:') !!}
                {!! Form::text('tax', isset($package) ? $package->tax:0, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">Multimedia del paquete</div>
        <div class="box-body">
            {!! Html::inputImage('Imagen de fondo (Desktop)','background_image', isset($package)?$package:null, \App\Models\Admin\Package::$GROUP_IMAGE, 'Imagen disponible para la versión Desktop') !!}
            {!! Html::inputImage('Imagen de fondo (Mobile)','background_image_mobile', isset($package)?$package:null, \App\Models\Admin\Package::$GROUP_MOBILE, 'Imagen disponible para la versión Mobile') !!}
            <div class="form-group col-sm-12">
                {!! Form::label('video_link', 'Video:') !!}
                {!! Form::text('video_link', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::text('order', isset($order) ? $order : null , ['class' => 'form-control']) !!}
</div>
<div class="clearfix"></div>

<div class="form-group col-sm-6">
    {!! Form::hidden('active', 0) !!}
    {!! Form::label('active', 'Activo?:') !!}
    {!! Form::checkbox('active', 1, isset($package) ? $package->active : 1) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('shareable', 0) !!}
    {!! Form::label('shareable', 'Se puede compartir?:') !!}
    {!! Form::checkbox('shareable', 1, isset($package) ? $package->shareable : 1) !!}
</div>
<div class="clearfix"></div>
@section('scripts')
    <script type="text/javascript">
        var config = {
            // Define the toolbar groups as it is a more accessible solution.
            toolbarGroups: [
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                '/',
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Source,Save,Templates,NewPage,Preview,Print,Cut,Undo,Redo,Copy,Paste,PasteText,PasteFromWord,Find,SelectAll,Scayt,Replace,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Strike,Superscript,CopyFormatting,RemoveFormat,NumberedList,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Link,Unlink,Anchor,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Font'
        }
        CKEDITOR.replace('more_info', config)
        CKEDITOR.replace('description', config);
    </script>
@endsection