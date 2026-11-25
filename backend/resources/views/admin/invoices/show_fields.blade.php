<div class="panel panel-info">
    <div class="panel-body">
        <div class="row">
            <div class="text-right col-md-2">Número de Pedido</div>
            <div class="text-left col-md-3"><b>{!! $invoice->order_number !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Estado</div>
            <div class="text-left col-md-3"><b>{!! __("messages.$invoice->status") !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Tienda</div>
            <div class="text-left col-md-3"><b>{!! $invoice->store->name !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Fecha</div>
            <div class="text-left col-md-3"><b>{!! $invoice->created_at->format('d/m/Y') !!}</b></div>
            <div class="col-md-1"></div>
            <div class="text-right col-md-2">Realizado por</div>
            <div class="text-left col-md-3"><b>{!! $invoice->user->email !!}</b></div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <h4 class="panel-heading">Productos</h4>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th class="text-left">Producto</th>
                <th class="text-left">Categoría</th>
                <th class="text-right">Cantidad</th>
            </tr>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{!! $item->model->name !!}</td>
                    <td>
                        {!! $item->model->family->name !!}
                        @if($item->model->subFamily)
                            -> {!! $item->model->subFamily->name  !!}
                        @endif
                    </td>
                    <td class="text-right">{!! $item->qty !!}</td>
                </tr>
            @endforeach

        </table>
    </div>
</div>