<script type="text/javascript">
    var cleanAll = function () {
        $('#frmFilter input').val('');
    }
</script>
{!! Form::open(['id'=>'frmFilter','route' => $route, 'method'=>'GET']) !!}
<div class="row row-filter">
        {!! $slot !!}
    <div class="col-md-2 text-right">
        <div class="btn-group btn-group">
            <a role="button" href="{!! route($route, ['clean'=>1]) !!}" class="btn btn-info" alt="Limpiar" title="Limpiar"><i class="fa fa-times"></i></a>
            <button role="button" type="submit" class="btn btn-primary" alt="Buscar" title="Buscar"><i class="fa fa-search"></i></button>
            @if(isset($route_export))
                <a title="Exportar a excel el resultado de la búsqueda" href="{{route($route_export, Request::all())}}" class="btn btn-success" role="button"><i class="fa fa-file-excel-o"></i></a>
            @endif
        </div>
    </div>
</div>
{!! Form::close() !!}
<hr/>