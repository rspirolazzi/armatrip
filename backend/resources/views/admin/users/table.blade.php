<table class="table table-responsive" id="users-table">
    <thead>
    <tr>
        <th class="hidden-xs">Nombre</th>
        <th class="hidden-xs">Apellido</th>
        <th class="hidden-xs">Teléfono</th>
        <th class="hidden-xs">Fec. Nac.</th>
        <th>Email</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td class="hidden-xs">{!! $user->name !!}</td>
            <td class="hidden-xs">{!! $user->last_name !!}</td>
            <td class="hidden-xs">{!! $user->telephone !!}</td>
            <td class="hidden-xs">{!! $user->birthday !!}</td>
            <td>{!! $user->email !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.users.show')
                        <a href="{!! route('admin.users.show', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.users.edit')
                        <a href="{!! route('admin.users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.users.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
