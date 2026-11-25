<table class="table table-responsive" id="orders-table">
    <thead>
    <tr>
        <th>Usuario</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{!! $order->user->name !!}</td>
            <td>
                {!! Form::open(['route' => ['orders.destroy', $order->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('orders.show', [$order->id]) !!}" class='btn btn-default btn-xs'><i
                                class="fa fa-eye"></i></a>
                    <a href="{!! route('orders.edit', [$order->id]) !!}" class='btn btn-default btn-xs'><i
                                class="fa fa-edit"></i></a>
                    @can('') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>