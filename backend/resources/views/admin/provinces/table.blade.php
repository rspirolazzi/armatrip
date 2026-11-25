<table class="table table-responsive" id="provinces-table">
    <thead>
    <tr>
        <th class="hidden-xs">#</th>
        <th class="hidden-xs">Código</th>
        <th>Nombre</th>
        <th class="hidden-xs">Recargo</th>
        <th>Activo?</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($provinces as $province)
        <tr>
            <td class="hidden-xs">{!! $province->id !!}</td>
            <td class="hidden-xs">{!! $province->code !!}</td>
            <td>{!! $province->name !!}</td>
            <td class="hidden-xs">{!! $province->tax !!}%</td>
            <td>{!! Form::faCheck($province->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.provinces.destroy', $province->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.provinces.show')
                    <a href="{!! route('admin.provinces.show', [$province->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.provinces.edit')
                    <a href="{!! route('admin.provinces.edit', [$province->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.provinces.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>