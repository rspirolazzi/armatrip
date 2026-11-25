<table class="table table-responsive" id="brands-table">
    <thead>
    <tr>
        <th class="hidden-xs">Código</th>
        <th>Nombre</th>
        <th class="hidden-xs">Descripción</th>
        <th class="hidden-xs">Orden</th>
        <th>Activo</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($brands as $brand)
        <tr>
            <td class="hidden-xs">{!! $brand->code !!}</td>
            <td>{!! $brand->name !!}</td>
            <td class="hidden-xs">{!! $brand->description !!}</td>
            <td class="hidden-xs">{!! $brand->order !!}</td>
            <td>{!! Form::faActive($brand->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.brands.destroy', $brand->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.brands.show')
                        <a href="{!! route('admin.brands.show', [$brand->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-eye-open"></i></a>
                    @endcan
                    @can('admin.brands.edit')
                        <a href="{!! route('admin.brands.edit', [$brand->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-edit"></i></a>
                    @endcan
                    @can('admin.brands.destroy')
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    @endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>