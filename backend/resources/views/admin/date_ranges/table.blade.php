<table class="table table-responsive" id="dateRanges-table">
    <thead>
        <tr>
            <th>Name</th>
        <th>Start At</th>
        <th>End At</th>
        <th>Active</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($dateRanges as $dateRange)
        <tr>
            <td>{!! $dateRange->name !!}</td>
            <td>{!! $dateRange->start_at !!}</td>
            <td>{!! $dateRange->end_at !!}</td>
            <td>{!! $dateRange->active !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.dateRanges.destroy', $dateRange->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.dateRanges.show')
                        <a href="{!! route('admin.dateRanges.show', [$dateRange->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.dateRanges.edit')
                        <a href="{!! route('admin.dateRanges.edit', [$dateRange->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.dateRanges.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>