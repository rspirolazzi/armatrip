<table class="table table-responsive" id="media_invoices-table">
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Número</th>
        <th>Tienda</th>
        <th>Usuario</th>
        <th>Enviado</th>
        <th>Estado</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @if(!isset($media_invoices)||sizeof($media_invoices)===0)
        <tr class="info">
            <td colspan="100">No hay pedidos cargados aún</td>
        </tr>
    @else
    @foreach($media_invoices as $media_invoice)
        <tr class="{!! $media_invoice->status == \App\Models\Admin\MediaInvoice::$STATUS_CONFIRMED ? 'warning' :($media_invoice->status == \App\Models\Admin\MediaInvoice::$STATUS_RECEIVED ? 'warning' :'') !!}">
            <td>{!! $media_invoice->created_at->format('d/m/Y') !!}</td>
            <td>{!! $media_invoice->order_number !!}</td>
            <td>{!! $media_invoice->store->name !!}</td>
            <td>{!! $media_invoice->user->email !!}</td>
            <td>{!! $media_invoice->sent_at?$media_invoice->sent_at->format('d/m/Y H:i'):''  !!}</td>
            <td>{!! __("messages.$media_invoice->status") !!}</td>
            <td>
                @if($media_invoice->status === \App\Models\Admin\MediaInvoice::$STATUS_DRAFT)
                    {!! Form::open(['route' => ['admin.media_invoices.destroy', $media_invoice->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        @can('admin.media_invoices.show')<a alt="Ver" title="Ver" href="{!! route('admin.media_invoices.show', [$media_invoice->id]) !!}" class='btn btn-gradient btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan
                        @can('admin.media_invoices.create')<a alt="Modificar" title="Modificar" href="{!! route('admin.media_invoices.create', [$media_invoice->id]) !!}" class='btn btn-gradient btn-default btn-xs'><i class="fa fa-edit"></i></a>@endcan
                        @can('admin.media_invoices.confirm')<a alt="Confirmar" title="Confirmar" href="{!! route('admin.media_invoices.confirm', [$media_invoice->id]) !!}" class='btn btn-gradient btn-success btn-xs'><i class="fa fa-check"></i></a>@endcan
                        @can('admin.media_invoices.destroy'){!! Form::button('<i class="fa fa-trash"></i>', ['alt'=>'Eliminar','title'=>'Eliminar', 'type' => 'submit', 'class' => 'btn btn-gradient btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro?')"]) !!}@endcan
                    </div>
                    {!! Form::close() !!}
                @else
                    <div class='btn-group'>
                        @can('admin.media_invoices.show')<a href="{!! route('admin.media_invoices.show', [$media_invoice->id]) !!}" class='btn btn-gradient btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
    @endif
    </tbody>
</table>