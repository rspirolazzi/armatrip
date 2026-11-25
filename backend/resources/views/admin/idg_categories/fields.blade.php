<div class="form-group col-sm-12">
    {!! Form::label('id', '#:') !!}
    {!! Form::text('id', null, ['class' => 'form-control', "disabled"]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
    {!! Form::label('description', 'Descripción:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', ]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Categoría:') !!}
    {!! Form::select('category_id', \App\Models\Admin\IDG\Category::getParents(['allowed_to_sell'=>1]) , null , ['class' => 'form-control', 'placeholder'=>'']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::text('order', isset($order) ? $order : null , ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::hidden('active', 0) !!}
    {!! Form::label('active', 'Activo?:') !!}
    {!! Form::checkbox('active', 1) !!}
</div>

{!! Form::hidden('allowed_to_sell', 1) !!}

<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary btn-gradient']) !!}
    <a href="{!! route('idg.categories.index') !!}" class="btn btn-default btn-gradient">Volver</a>
</div>
@section('scripts')
    <script type="text/javascript">
        CKEDITOR.replace('description', {
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
        });
    </script>
@endsection