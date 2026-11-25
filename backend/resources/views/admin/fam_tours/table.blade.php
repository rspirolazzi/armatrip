<table class="table table-responsive">
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Nombre</th>
        <th>From</th>
        <th class="hidden-xs">Télefono</th>
        <th class="hidden-xs">Colegio</th>
        <th class="hidden-xs">Localidad</th>
        <th class="hidden-xs">Asunto</th>
        <th>Categoría</th>
        <th>Calendario</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($fam_tours as $fam_tour)
        <tr>
            <td>{!! $fam_tour->date->format('d/m/Y H:i') !!} @if($fam_tour->moved == true)
                <i title="Este mensaje fue movido" class="fa fa-folder-open"></i>
                @endif</td>
            <td>{!! $fam_tour->name !!}</td>
            <td>{!! $fam_tour->from !!}</td>
            <td class="hidden-xs">{!! $fam_tour->phone !!}</td>
            <td class="hidden-xs">{!! $fam_tour->school !!}</td>
            <td class="hidden-xs">{!! $fam_tour->city !!}</td>
            <td class="hidden-xs">{!! $fam_tour->subject !!}</td>
            <td>@foreach($fam_tour->tags as $tag)
                <small class="label label-info">{!! $tag->name !!}</small>
            @endforeach</td>
            <td>@if($fam_tour->link_google_calendar)
                @if(isset($fam_tour->calendar))
                {!!$fam_tour->calendar->format('d/m/Y')!!}
                @endif
                <a target="_blank" href="{!! $fam_tour->link_google_calendar !!}">
                <i title="Generar una agenda en Google calendar" class="fa fa-calendar"></i>
                </a>
                @endif
            </td>
            <td>
                <div class='btn-group'>
                    @can('admin.fam_tours.show')<a alt="Ver" title="Ver" href="{!! route('admin.fam_tours.show', [$fam_tour->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan
                    @can('admin.fam_tours.edit')<a alt="Editar" title="Editar" href="{!! route('admin.fam_tours.edit', [$fam_tour->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-pencil"></i></a>@endcan
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@section('scripts')
<script type="text/javascript">
    $(function(){
        $('#tagModal').on('show.bs.modal', function(e){
            var data = $(e.relatedTarget).data()
            beforeOpenModal(data)

            $('#category').tagsInput({
                'autocomplete': {
                    source: {!! json_encode($tags) !!}
                },
                'placeholder':'Escriba el nombre de la categoría y presione , (coma) para seguir agregando'
            });
        })
        
        $('#tagModal').on('hidden.bs.modal', function(e){
            $('#category').destroyTag();
        })

        var beforeOpenModal = function(data){
            $(Object.keys(data)).each(function(i,v){
                if($('#'+v).length>0){
                    switch($('#'+v)[0].type){
                        case 'text':
                        case 'hidden':
                            $('#tagModal #'+v).val(data[v])
                            break;
                        default:
                            $('#tagModal #'+v).text(data[v])
                            break;
                    }
                }
            });
        }
    })
</script>
@endsection
<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="tagModalLabel">Agregar o quitar categorías</h4>
            </div>
            {!! Form::open(['route' => ['admin.fam_tours.update'], 'method' => 'post']) !!}
            <div class="modal-body">
                <p>Mensage original:</p>
                <pre id="body"></pre>
                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::label('category', 'Categoria:') !!}
                        {!! Form::text('category', null, ['class' => 'form-control']) !!}
                        <p class="help-block">Escriba el nombre de la categoría y presione , (coma) para seguir agregando</p>
                    </div>
                    {!! Form::hidden('id', null, ['id'=>'id']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>