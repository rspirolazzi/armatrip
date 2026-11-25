<div class="form-group col-sm-12">
    {!! Form::label('message_id', 'Mensaje ID:') !!}
    <p class="form-control form-control-static">{!!$fam_tour->message_id!!}</p>
</div>
<div class="col-sm-8">
    <div class="panel panel-info">
        <h5 class="panel-heading">Datos editables</h5>
        <div class="panel-body">
            <div class="form-group col-sm-6">
                {!! Form::label('name', 'Nombre:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->name!!}</p>
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('phone', 'Teléfono:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->phone!!}</p>
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('student', 'Alumno:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->student!!}</p>
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('school', 'Colegio:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->school!!}</p>
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('city', 'Localidad:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->city!!}</p>
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('category', 'Categoria:') !!}
                <p class="form-control form-control-static">
                    @foreach($fam_tour->tags as $tag)
                        <small class="label label-info">{!! $tag->name !!}</small>
                    @endforeach
                </p>
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('calendar', 'Fecha de cita:') !!}
                <p class="form-control form-control-static">{!!isset($fam_tour) && isset($fam_tour->calendar) ? $fam_tour->calendar->format('d/m/Y') : ''!!}</p>
            </div>
            @if($fam_tour->link_google_calendar)
            <div class="form-group col-sm-6">
                {!! Form::label('link_google_calendar', 'Google Calendar:') !!}
                <p class="form-control form-control-static">
                <a target="_blank" href="{!! $fam_tour->link_google_calendar !!}">
                <i title="Generar una agenda en Google calendar" class="fa fa-calendar"></i> Generar un evento en Google Calendar
                </a>
                </p>
            </div>
            @endif

            <div class="form-group col-sm-12">
                <a href="{!! route('admin.fam_tours.index') !!}" class="btn btn-default btn-gradient">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="panel panel-info">
        <h5 class="panel-heading">Datos originales del email</h5>
        <div class="panel-body">
            <div class="form-group col-sm-12">
                {!! Form::label('body', 'Mensaje original:') !!}
                <div style="height: auto" class="form-control form-control-static">{!!$fam_tour->originalBody!!}</div>
            </div>
            
            <div class="form-group col-sm-12">
                {!! Form::label('subject', 'Asunto:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->subject!!}</p>
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('date', 'Fecha de envio:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->date!!}</p>
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('from', 'De:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->from!!}</p>
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('folder', 'Carpeta original:') !!}
                <p class="form-control form-control-static">{!!$fam_tour->folder!!}</p>
            </div>
        </div>
    </div>
</div>