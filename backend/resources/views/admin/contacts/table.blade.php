<table class="table table-responsive" id="contacts-table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th class="hidden-xs">Celular</th>
        <th class="hidden-xs">Comentario</th>
        <th>Fecha</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($contacts as $contact)
        <tr>
            <td>{!! $contact->name !!}</td>
            <td class="hidden-xs">{!! $contact->phone !!}</td>
            <td class="hidden-xs">{!! $contact->comment !!}</td>
            <td>{!! $contact->created_at->format('d-m-Y H:i')!!}</td>
            <td>
                {!! Form::open(['route' => ['admin.contacts.destroy', $contact->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.contacts.show', [$contact->id])
                    <a href="{!! route('admin.contacts.show', [$contact->id]) !!}" class='btn btn-default btn-xs'><i
                                class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.contacts.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>