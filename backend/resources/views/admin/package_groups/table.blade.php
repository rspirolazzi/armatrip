<table class="table table-responsive" id="packageGroups-table">
    <thead>
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Estado</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($packageGroups as $packageGroup)
        <tr>
            <td>{!! $packageGroup->code !!}</td>
            <td>{!! $packageGroup->name !!}</td>
            <td>{!! Form::faActive($packageGroup->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.packageGroups.destroy', $packageGroup->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.packageGroups.show', [$packageGroup->id]) !!}"
                       class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.packageGroups.edit', [$packageGroup->id]) !!}"
                       class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>