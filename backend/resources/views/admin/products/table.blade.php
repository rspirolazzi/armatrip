<table class="table table-responsive" id="products-table">
    <thead>
    <tr>
        <th class="hidden-xs">Código</th>
        <th>Nombre</th>
        <th class="hidden-xs">Categoria</th>
        <th>Price</th>
        <th>Estado</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td class="hidden-xs">{!! $product->id !!}</td>
            <td>{!! $product->name !!}</td>
            <td class="hidden-xs">{!! isset($product->category) ? $product->category->name : 'N/A'!!}</td>
            <td>{!! App\Helpers\Helper::formatPrice($product->price) !!}</td>
            <td>{!! !$product->allowed_to_sell ? ($product->active ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">No Activo</span>') : '<span class="label label-default">Uso interno</span>' !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.products.destroy', $product->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.products.show')
                        <a href="{!! route('admin.products.show', [$product->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.products.edit')
                        <a href="{!! route('admin.products.edit', [$product->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.products.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>