<table class="table table-responsive" id="categories-table">
    <thead>
        <tr>
            <th class="hidden-xs">#</th>
            <th class="hidden-xs">Código</th>
            <th>Nombre</th>
            <th class="hidden-xs">Orden</th>
            <th class="hidden-xs">Multiple seleccion</th>
            <th class="hidden-xs">Obligatorio</th>
            <th>Activo</th>
            <th colspan="3"></th>
        </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td class="hidden-xs">{!! $category->id !!}</td>
            <td class="hidden-xs">{!! $category->code!!}</td>
            <td>{!! $category->name !!}</td>
            <td class="hidden-xs">{!! $category->order!!}</td>
            <td class="hidden-xs">{!! Form::faCheck($category->multiple) !!}</td>
            <td class="hidden-xs">{!! Form::faCheck($category->required) !!}</td>
            <td>{!! Form::faActive($category->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.categories.destroy', $category->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.categories.show')
                        <a href="{!! route('admin.categories.show', [$category->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.categories.edit')
                        <a href="{!! route('admin.categories.edit', [$category->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.categories.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>