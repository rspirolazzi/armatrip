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
            <tr>
                <th class="col-xs-4 text-left">Producto</th>
                <th class="col-xs-3 text-left">Categoría</th>
                <th class="col-xs-1 text-center">Precio</th>
                <th class="col-xs-2 text-center">Cantidad</th>
                <th class="col-xs-1 text-right">Total</th>
                <th class="col-xs-1 text-right"></th>
            </tr>
            <?php
            $items = $media_invoice->items->sortBy('id');
            ?>
            @if(sizeof( $items)>0)
                @foreach($items as $index => $item)
                    <?php
                        //dd($item);
                    ?>
                    <tr>
                        <td>@can('admin.media_invoices.category')<a href="{!! route('admin.media_invoices.category' , [$item->model->category_id]) !!}#product_id_{!! $item->model->id !!}">{!! $item->model->name !!}</a>@endcan</td>
                        <td>
                            {!! $item->model->category->name !!}
                            @if($item->model->subFamily)
                                -> {!! $item->model->subFamily->name  !!}
                            @endif
                        </td>
                        <td class="text-right">{!! App\Helpers\Helper::formatPrice($item->price) !!}
                        <td class="text-right">{!! Form::number('items['.$item->id.']qty', $item->qty, ['class' => 'form-control', 'id'=>'qty_'.$item->id]) !!}
                        <td class="text-right">{!! App\Helpers\Helper::formatPrice($item->total) !!}
                        <td class="text-right">
                            {{--<button onclick="jQuery('#qty_{!! $item->id !!}').val(0)" class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>--}}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="info">
                    <td colspan="100%">Su carrito esta vacio</td>
                </tr>
            @endif
        </table>
    </div>
</div>