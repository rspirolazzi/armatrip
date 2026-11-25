<div class="row no-margin">
    @foreach($actions as $action)
        @can($action['route'])
            <div class="col-xs-6">
                <a class="{!! $action['btnClass'] !!} {{ Request::is($action['request_is']) ? 'active' : '' }}" href="{!! route($action['route']) !!}">
                    <i class="{!! $action['icon'] !!}"></i>
                    <p class="hidden-xs">{!! $action['title'] !!}</p>
                </a>
            </div>
        @endcan
    @endforeach
</div>