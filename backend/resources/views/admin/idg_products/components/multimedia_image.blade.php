@if(!isset($hidden) || !$hidden)
{!! Form::radio('option_'.$inputName, 'file', $attributes &&$attributes['type']==='file', ['id'=>'option_file_'.$inputName.'']) !!}
@else
{!! Form::hidden('option_'.$inputName, 'file') !!}
@endif
{!! Form::label('option_file_'.$inputName.'', $label) !!}
{!! Form::file('file_'.$inputName, ['class' => 'form-control']) !!}
<p class="help-block">{!! $helpBlock !!}</p>
@if($attributes &&$attributes['type']==='file')
    <a href="/storage/{!! $attributes['source'] !!}" target="_blank">
        <img src="/storage/{!! $attributes['source'] !!}" style="max-width: 100px"/>
        <p>
            <small>({!! $attributes[0] !!}
                x{!! $attributes[1] !!})
            </small>
        </p>
    </a>
    <div>
        {!! Form::checkbox('_delete_'.$inputName,  $attributes['source'], null, ['id'=>'delete_file_'.$inputName.'']) !!}
        {!! Form::label('delete_file_'.$inputName, '¿Eliminar?:') !!}
    </div>
@endif