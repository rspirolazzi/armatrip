<table class="table table-responsive" id="stores-table">
    <thead>
        <tr>
            <th class="hidden-xs">Código</th>
            <th>Nombre</th>
            <th>Moneda</th>
            <th>Activo</th>
            <th colspan="3"></th>
        </tr>
    </thead>
    <tbody>
    @foreach($stores as $store)
        <tr class="{!! $turismo_individual == $store->code ? 'info' : '' !!}">
            <td class="hidden-xs">{!! $store->code !!}</td>
            <td>{!! $store->name !!}</td>
            <td>{!! $store->currency_id ? $store->currency->symbol : '' !!}</td>
            <td>{!! Form::faActive($store->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.stores.destroy', $store->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.stores.show')
                        <a href="{!! route('admin.stores.show', [$store->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.stores.edit')
                        <a href="{!! route('admin.stores.edit', [$store->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.stores.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>