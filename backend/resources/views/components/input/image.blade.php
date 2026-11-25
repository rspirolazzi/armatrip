<div class="form-group col-sm-6">
    {!! Form::label($name, $title) !!}
    {!! Form::file($name, null, ['class' => 'form-control']) !!}
    @if(isset($model) && $model->getFirstMedia($group))
        {!! Form::hidden($name.'_media_id',  $model->getFirstMedia($group)->id) !!}
    @endif
    @if($helper)
        <p class="text-muted">{!! $helper !!}</p>
    @endif
</div>
<div class="form-group col-sm-6">
    @if(isset($model) && $model->getFirstMedia($group))
        <a href="{!! $model->getFirstMediaUrl($group) !!}" target="_blank" class="thumbnail">
            <img src="{!! $model->getFirstMediaUrl($group, \App\Models\Admin\Package::$IMAGE_CONVERSION_THUMBNAIL) !!}" alt="{!! $model->name !!}" style="max-height: 200px">
        </a>
        {!! Form::checkbox('_delete_'.$name,  $model->getFirstMedia($group)->id, null, ['id'=>'_delete_'.$name]) !!}
        {!! Form::label('delete_file_'.$name, '¿Eliminar?:') !!}
    @endif
</div>