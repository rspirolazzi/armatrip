{!! Form::open(['route' => 'admin.settings.update', 'method' => 'PUT']) !!}
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($settings as $item)
                <tr>
                    <td class="col-md-4">
                        {!! $item['label'] !!}
                        <br/><small>key:{!!$item['key']!!}</small>
                    </td>
                    <td class="col-md-8">
                            @component('admin.settings.components.input', $item)
                            @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-12 text-right">
        <a href="{!!route('admin.settings.index')!!}" class="btn btn-default">Volver</a>
        @can('admin.settings.index')
            <button type="submit" class="btn btn-primary">Guardar</button>@endcan
    </div>

</div>
{!! Form::close() !!}