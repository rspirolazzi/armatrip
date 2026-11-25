<div class="col-sm-8">
    <div class="panel panel-info">
        <h5 class="panel-heading">Datos editables</h5>
        <div class="panel-body">
            <div class="form-group col-sm-6">
                {!! Form::label('name', 'Nombre:') !!}
                {!! Form::text('name', null , ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('phone', 'Teléfono:') !!}
                {!! Form::text('phone', null , ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('student', 'Alumno:') !!}
                {!! Form::text('student', null , ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('school', 'Colegio:') !!}
                {!! Form::text('school', null , ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('city', 'Localidad:') !!}
                {!! Form::text('city', null , ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('category', 'Categoria:') !!}
                {!! Form::text('category', null, ['class' => 'form-control']) !!}
                <p class="help-block">Escriba el nombre de la categoría y presione , (coma) para seguir agregando</p>
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('calendar', 'Fecha de cita:') !!}
                {!! Form::date('calendar', isset($fam_tour) && isset($fam_tour->calendar) ? $fam_tour->calendar->format('Y-m-d') : null , [
                    'class' => 'form-control',
                    'min'=>isset($fam_tour) && isset($fam_tour->calendar) ? $fam_tour->calendar->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')
                    ]) !!}
            </div>
            <!--<div class="form-group col-sm-6">
                {!! Form::label('link_google_calendar', 'Google:') !!}
                {!! Form::text('link_google_calendar', null , ['class' => 'form-control', 'disabled']) !!}                
            </div>-->
            <div class="form-group col-sm-12">
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary btn-gradient']) !!}
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
                {!! Form::textarea('originalBody', null , ['class' => 'form-control', "disabled"]) !!}
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('message_id', 'Mensaje ID:') !!}
                {!! Form::text('message_id', null, ['class' => 'form-control', "disabled"]) !!}
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('subject', 'Asunto:') !!}
                {!! Form::text('subject', null, ['class' => 'form-control', "disabled"]) !!}
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('date', 'Fecha de envio:') !!}
                {!! Form::text('date', null, ['class' => 'form-control', "disabled"]) !!}
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('from', 'De:') !!}
                {!! Form::text('from', null , ['class' => 'form-control', "disabled"]) !!}
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('folder', 'Carpeta original:') !!}
                {!! Form::text('folder', null , ['class' => 'form-control', "disabled"]) !!}
            </div>
        </div>
    </div>
</div>