<div class="panel panel-info">
    <div class="panel-body">
        <div class="row">
            <div class="text-right col-md-2">Número de Pedido</div>
            <div class="text-left col-md-3"><b>{!! $media_invoice->order_number !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Estado</div>
            <div class="text-left col-md-3"><b>{!! __("messages.$media_invoice->status") !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Tienda</div>
            <div class="text-left col-md-3"><b>{!! $media_invoice->store->name !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Fecha</div>
            <div class="text-left col-md-3"><b>{!! $media_invoice->created_at->format('d/m/Y') !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Realizado por</div>
            <div class="text-left col-md-3"><b>{!! $media_invoice->user->email !!}</b></div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <h4 class="panel-heading">Productos</h4>
    <div class="panel-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="col-xs-4 text-left">Producto</th>
                <th class="col-xs-3 text-left">Categoría</th>
                <th class="col-xs-1 text-center">Precio</th>
                <th class="col-xs-2 text-center">Cantidad</th>
                <th class="col-xs-2 text-right">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($media_invoice->items->sortBy('id') as $index => $item)
                <tr>
                    <td>@can('admin.media_invoices.category')<a href="{!! route('admin.media_invoices.category' , [$item->model->category_id]) !!}#product_id_{!! $item->model->id !!}">{!! $item->model->name !!}</a>@endcan</td>
                    <td>
                        {!! $item->model->category->name !!}
                        @if($item->model->subFamily)
                            -> {!! $item->model->subFamily->name  !!}
                        @endif
                    </td>
                    <td class="text-right">{!! App\Helpers\Helper::formatPrice($item->price) !!}
                    <td class="text-right">{!! $item->qty !!}
                    <td class="text-right">{!! App\Helpers\Helper::formatPrice($item->total) !!}
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">{!!  App\Helpers\Helper::formatPrice($media_invoice->total) !!}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>