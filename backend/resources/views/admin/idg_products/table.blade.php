<table class="table table-responsive" id="products-table">
    <thead>
    <tr>
        <th class="hidden-xs">#</th>
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
            <td class="hidden-xs">{!! \Illuminate\Support\Str::limit($product->code, 30) !!}</td>
            <td>{!! \Illuminate\Support\Str::limit($product->name, 30) !!}</td>
            <td class="hidden-xs">{!! isset($product->category) ? \Illuminate\Support\Str::limit($product->category->fullName, 30) : 'N/A'!!}</td>
            <td>{!! App\Helpers\Helper::formatPrice($product->price) !!}</td>
            <td>{!! Form::faActive($product->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['idg.products.destroy', $product->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('idg.products.show')
                        <a href="{!! route('idg.products.show', [$product->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-eye"></i></a>
                    @endcan
                    @can('idg.products.edit')
                        <a href="{!! route('idg.products.edit', [$product->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-edit"></i></a>
                    @endcan
                    @can('idg.products.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>