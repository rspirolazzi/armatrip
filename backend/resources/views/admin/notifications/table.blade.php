<table class="table table-responsive" id="notifications-table">
    <thead>
    <tr>
        <th class="hidden-xs">Código</th>
        <th>Titulo</th>
        <th class="hidden-xs">Duración</th>
        <th>Type</th>
        <th class="hidden-xs">Orden</th>
        <th class="hidden-xs">Uri</th>
        <th>Estado</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($notifications as $notification)
        <tr>
            <td class="hidden-xs">{!! $notification->code !!}</td>
            <td>{!! $notification->title !!}</td>
            <td class="hidden-xs">{!! $notification->duration !!}</td>
            <td>{!! $notification->type !!}</td>
            <td class="hidden-xs">{!! $notification->order !!}</td>
            <td class="hidden-xs">{!! $notification->uri !!}</td>
            <td>{!! Form::faActive($notification->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.notifications.destroy', $notification->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.notifications.show')
                        <a href="{!! route('admin.notifications.show', [$notification->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @endcan
                    @can('admin.notifications.edit')
                        <a href="{!! route('admin.notifications.edit', [$notification->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    @endcan
                    @can('admin.notifications.destroy'){!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>