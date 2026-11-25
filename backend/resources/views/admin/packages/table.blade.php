<table class="table table-responsive" id="packages-table">
    <thead>
    <tr>
        <th class="hidden-xs">#</th>
        <th class="hidden-xs">Código</th>
        <th>Nombre</th>
        <th class="hidden-xs">Empresa</th>
        <th>Price</th>
        <th>Estado</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($packages as $package)
        <tr>
            <td class="hidden-xs">{!! $package->id !!}</td>
            <td class="hidden-xs">{!! $package->code!!}</td>
            <td>{!! $package->name !!}</td>
            <td class="hidden-xs">{!! isset($package->store) ? $package->store->name : '' !!}</td>
            <td>{!! App\Helpers\Helper::formatPrice($package->price) !!}</td>
            <td>{!! Form::faActive($package->active) !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.packages.destroy', $package->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.packages.show')
                        <a href="{!! route('admin.packages.show', [$package->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-eye"></i></a>
                    @endcan
                    @can('admin.packages.edit')
                        <a href="{!! route('admin.packages.edit', [$package->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="fa fa-edit"></i></a>
                    @endcan
                    @can('admin.packages.clone')
                        <button data-toggle="modal" data-target="#cloneModal"
                                data-id="{!! $package->id !!}"
                                data-code="{!! $package->code !!}"
                                data-price="{!! $package->price !!}"
                                data-name="{!! $package->name !!}"
                                data-store="{!! $package->store_id !!}"
                                type="button" class="btn btn-warning btn-xs hidden-xs" alt="Copiar" title="Copiar"><i
                                    class="fa fa-copy"></i></button>@endcan
                    @can('admin.packages.destroy') {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs hidden-xs', 'onclick' => "return confirm('¿Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="modal fade" id="cloneModal" tabindex="-1" role="dialog" aria-labelledby="cloneModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="cloneModalLabel">New message</h4>
            </div>
            {!! Form::open(['route' => ['admin.packages.clone'], 'method' => 'post']) !!}
            <div class="modal-body">
                <p class="text-warning"><i class="fa fa-warning"></i> Estas a punto de copiar este paquete con todas sus opciones.</p>
                <p class="text-warning">Podes modificar los siguientes valores para el nuevo paquete</p>
                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::label('code', 'Código:') !!}
                        {!! Form::text('code', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('name', 'Nombre:') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', ]) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('price', 'Precio:') !!}
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            {!! Form::number('price', null, ['class' => 'form-control']) !!}
                        </div>
                        <p class="text-muted">Precio base del paquete por persona</p>
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('store_id', 'Empresa:') !!}
                        {!! Form::select('store_id', \App\Models\Store::pluck('name', 'id'), null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::hidden('id', null, ['id'=>'id']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Copiar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#cloneModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var data = button.data()
                var modal = $(this)

                modal.find('.modal-title').html('Copiar paquete <b>' + data.name + '</b>')

                modal.find('.modal-body #id').val(data.id);
                modal.find('.modal-body #code').val(data.code + '_' + data.id);
                modal.find('.modal-body #name').val(data.name);
                modal.find('.modal-body #price').val(data.price);
                modal.find('.modal-body #store_id').val(data.store);
            })
        });
    </script>
@endsection