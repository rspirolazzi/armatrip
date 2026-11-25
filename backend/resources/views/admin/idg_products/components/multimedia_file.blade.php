@if(!isset($hidden) || !$hidden)
{!! Form::radio('option_'.$inputName, 'url', $attributes&&$attributes['type']==='url', ['id'=>'option_url_'.$inputName.'']) !!}
@else
{!! Form::hidden('option_'.$inputName, 'url') !!}

@endif
{!! Form::label('option_url_'.$inputName.'', $label) !!}
{!! Form::text('url_'.$inputName, ($attributes&&$attributes['type']==='url') ? $attributes['source'] : null , ['class' => 'form-control']) !!}
<p class="help-block">{!! $helpBlock !!}</p>
@if($attributes&&$attributes['type']==='url')
    <a href="{!! $attributes['source'] !!}" target="_blank">
        {!! $attributes['source']!!}
    </a>
@endif