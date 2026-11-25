<!-- Código Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=title]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>

<!-- Titulo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Titulo:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Mensaje Field -->
<div class="form-group col-sm-12">
    {!! Form::label('message', 'Mensaje:') !!}
    {!! Form::textarea('message', null, ['class' => 'ckeditor']) !!}
</div>

<!-- Duración Field -->
<div class="form-group col-sm-6">
    {!! Form::label('duration', 'Duración (en segundos):') !!}
    {!! Form::number('duration', null, ['class' => 'form-control']) !!}
</div>

<!-- Orden Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::number('order', null, ['class' => 'form-control']) !!}
</div>

<!-- Uri Field -->
<div class="form-group col-sm-6">
    {!! Form::label('uri', 'Uri:') !!}
    <autocomplete-component
            name="uri"
            placeholder="Escriba el nombre de un paquete"
            :source="{{ $urls }}"
            :min="1"
            value="{!! isset($notification)? $notification->uri : '' !!}"
    />
</div>

<!-- Type Field -->
<div class="form-group col-sm-12">
    {!! Form::label('type', 'Tipo:') !!}
    <div class="row">
        @foreach(\App\Models\Admin\Notification::getTypeOptions() as $type=>$label)
            <div class="col-md-3">
                <div class="alert alert-{!! $type !!}" data-target="select" data-value="{!! $type !!}">
                    {!! Form::radio('type', $type, isset($notification) ? ($type === $notification->type ? $type : null) : (\App\Models\Admin\Notification::$TYPE_INFO === $type ? $type : null), ['id'=>'type_'.$type]) !!}
                    @switch($type)
                        @case(\App\Models\Admin\Notification::$TYPE_DANGER)
                        <i class="fa fa-ban"></i>
                        @break
                        @case(\App\Models\Admin\Notification::$TYPE_SUCCESS)
                        <i class="fa fa-check-circle"></i>
                        @break
                        @case(\App\Models\Admin\Notification::$TYPE_WARNING)
                        <i class="fa fa-warning"></i>
                        @break
                        @case(\App\Models\Admin\Notification::$TYPE_INFO)
                        <i class="fa fa-info-circle"></i>
                        @break
                    @endswitch
                    <b>Titulo para {!! $label !!}</b>
                    <p>Mensaje para {!! $label !!}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
<?php
$rules = '[]';
if (isset($notification) && $notification->rules) {
    $rules = $notification->rules;
}
?>
<div class="form-group col-sm-12">
    <action-rules-component
            :is-active="true"
            name="is_actions"
            :values="{{ $rules }}"
            :checked="{!! isset($notification) && $notification->is_actions == 1 ? 1 : 0 !!}"
            :list-of-products="{{ $products }}"
            :list-of-actions="[
            {id:'{!! \App\Http\Controllers\Admin\NotificationController::$ACTION_SELECT !!}', name:'se selecciona'},
            {id:'{!! \App\Http\Controllers\Admin\NotificationController::$ACTION_UN_SELECT !!}', name:'se saca la selección'}]"

    />
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('is_custom', 0) !!}
    {!! Form::label('is_custom', '¿Usar mensajes redondos?:')	!!}
    {!! Form::checkbox('is_custom', 1,  (!isset($notification)  ? 1 : null), ['id' => 'is_custom']) !!}
    <p class="help-block">Si esta opción es marcada, los mensajes (POP UP) seran mostrados con estilo redondos.</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('active', 0) !!}
    {!! Form::label('active', 'Habilitado:')	!!}
    {!! Form::checkbox('active', 1,  (!isset($notification) ? 1 : null), ['id' => 'active']) !!}
    <p class="help-block">Determina si la notificación se muestra en la web.</p>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.notifications.index') !!}" class="btn btn-default">Cancelar</a>
</div>
@section('scripts')
    <script type="text/javascript">
        CKEDITOR.replace('message', {
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
    <script type="text/javascript">
        $(function () {
            $('[data-target=select]')
                .on('click', function (e) {
                    var type = $(this).data('value');
                    $('#type_' + type).prop('checked', true);
                })
                .css('cursor', 'pointer')
            ;
        });
    </script>
@endsection
<script>
    import AutoComplete from "../../../assets/js/components/AutoComplete";
    export default {
        components: {AutoComplete}
    }
</script>