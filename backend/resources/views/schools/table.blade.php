<table class="table table-responsive" id="schools-table">
    <thead>
        <tr>
            <th>Name</th>
        <th>Year</th>
        <th>Division</th>
        <th>Turn</th>
        <th>Address</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($schools as $school)
        <tr>
            <td>{!! $school->name !!}</td>
            <td>{!! $school->year !!}</td>
            <td>{!! $school->division !!}</td>
            <td>{!! $school->turn !!}</td>
            <td>{!! $school->address !!}</td>
            <td>
                {!! Form::open(['route' => ['schools.destroy', $school->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('schools.show', [$school->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('schools.edit', [$school->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @can('') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>