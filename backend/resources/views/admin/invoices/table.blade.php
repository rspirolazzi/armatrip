<table class="table table-responsive" id="invoices-table">
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
    @foreach($invoices as $invoice)
        <tr class="{!! $invoice->status == \App\Models\Admin\Invoice::$STATUS_CONFIRMED ? 'warning' :($invoice->status == \App\Models\Admin\Invoice::$STATUS_RECEIVED ? 'warning' :'') !!}">
            <td>{!! $invoice->created_at->format('d/m/Y') !!}</td>
            <td>{!! $invoice->order_number !!}</td>
            <td>{!! $invoice->store->name !!}</td>
            <td>{!! $invoice->user->email !!}</td>
            <td>{!! $invoice->sent_at?$invoice->sent_at->format('d/m/Y H:i'):''  !!}</td>
            <td>{!! __("messages.$invoice->status") !!}</td>
            <td>
                @if($invoice->status === \App\Models\Admin\Invoice::$STATUS_DRAFT)
                    {!! Form::open(['route' => ['admin.invoices.destroy', $invoice->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        @can('admin.invoices.show')<a alt="Ver" title="Ver" href="{!! route('admin.invoices.show', [$invoice->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan
                        @can('admin.invoices.edit')<a alt="Modificar" title="Modificar" href="{!! route('admin.invoices.edit', [$invoice->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>@endcan
                        @can('admin.invoices.confirm')<a alt="Confirmar" title="Confirmar" href="{!! route('admin.invoices.confirm', [$invoice->id]) !!}" class='btn btn-primary btn-xs'><i class="fa fa-check"></i></a>@endcan
                        @can('admin.invoices.print')<a alt="Imprimir" title="Imprimir" href="{!! route('admin.invoices.print', [$invoice->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-print"></i></a>@endcan
                        @can('admin.invoices.destroy'){!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('Estas seguro?')"]) !!}@endcan
                    </div>
                    {!! Form::close() !!}
                @else
                    <div class='btn-group'>
                        @can('admin.invoices.show')<a alt="Ver" title="Ver" href="{!! route('admin.invoices.show', [$invoice->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan
                        @can('admin.invoices.print')<a alt="Imprimir" title="Imprimir" href="{!! route('admin.invoices.print', [$invoice->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-print"></i></a>@endcan
                    </div>
                @endif


            </td>
        </tr>
    @endforeach
    </tbody>
</table>