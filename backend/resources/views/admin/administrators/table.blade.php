<table class="table table-responsive" id="users-table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th class="hidden-xs">Roles</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{!! $user->name !!}</td>
            <td>{!! $user->email !!}</td>
            <td  class="hidden-xs">{!! $user->getRoleNames() !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.administrators.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.administrators.show')
                        <a href="{!! route('admin.administrators.show', [$user->id]) !!}"
                           class='btn btn-default btn-xs'><i
                                    class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.administrators.edit')
                        <a href="{!! route('admin.administrators.edit', [$user->id]) !!}"
                           class='btn btn-default btn-xs'><i
                                    class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.administrators.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
