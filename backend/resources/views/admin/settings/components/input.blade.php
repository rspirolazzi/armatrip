<div class="form-group">
    @switch($type)
        @case(\App\Models\Admin\SettingManager::$TYPE_BOOLEAN)
        {!! Form::hidden("values[$key]", 0) !!}
        {!! Form::checkbox("values[$key]", 1, (bool)$value) !!}
        @break
        @case(\App\Models\Admin\SettingManager::$TYPE_SELECT)
            @if(isset($options['from_options']))
                {!! Form::select("values[$key]", $options['from_options']['options'], $value, ['class'=>"form-control input-sm"]) !!}
            @elseif(isset($options['from_model']))
                {!! Form::select("values[$key]", $options['from_model']['model']::pluck($options['from_model']['option_display'], $options['from_model']['option_key']), $value, ['class'=>"form-control input-sm"]) !!}
            @endif
        @break
        @case(\App\Models\Admin\SettingManager::$TYPE_INTEGER)
        @case(\App\Models\Admin\SettingManager::$TYPE_DECIMAL)
        {!! Form::number("values[$key]", $value, ['class'=>"form-control input-sm"]) !!}
        @break
        @default
        {!! Form::text("values[$key]", $value, ['class'=>"form-control input-sm"]) !!}
    @endswitch
    @if($description)
    <small class="help-block"><i class="fa fa-info-circle"></i> {!!$description!!}</small>
    @endif
</div>