<table class="table table-responsive" id="schools-table">
    <thead>
    <tr>
        <th class="hidden-xs">Código</th>
        <th>Nombre</th>
        <th class="hidden-xs">Localidad</th>
        <th class="hidden-xs">Provincia</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($schools as $school)
        <tr>
            <td class="hidden-xs">{!! $school->code !!}</td>
            <td>{!! $school->name !!}</td>
            <td class="hidden-xs">{!! $school->city !!}</td>
            <td class="hidden-xs">{!! $school->province !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.schools.destroy', $school->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.schools.show', [$school->id]) !!}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.schools.edit', [$school->id]) !!}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>