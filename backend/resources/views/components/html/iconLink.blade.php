@can($route)
    <a href="{!! route($route, $params) !!}" {!! trim(join(' ',collect($attributes)->map(function($value, $key){
        return "{$key}=\"{$value}\"";
        })->toArray())) !!}><i class="fa fa-{{$icon}}"></i>
    </a>
@endcan