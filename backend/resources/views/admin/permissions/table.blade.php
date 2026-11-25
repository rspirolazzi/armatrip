<table class="table table-responsive" id="users-table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Roles</th>
    </tr>
    </thead>
    <tbody>
    @foreach($permissions as $permission)
        <tr>
            <td>{!! $permission->name !!}</td>
            <td>{!! $permission->roles->map(function($role){return $role->name;}) !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
