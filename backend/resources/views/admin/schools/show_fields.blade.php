<div class="col-md-12">
    <div class="panel panel-info">
        <h5 class="panel-heading">Datos del colegio</h5>
        <div class="panel-body">
            <!-- Code Field -->
            <div class="form-group col-md-6">
                {!! Form::label('code', 'Código:') !!}
                <p class="form-control form-control-static">{!! $school->code !!}</p>
            </div>

            <!-- Name Field -->
            <div class="form-group col-md-6">
                {!! Form::label('name', 'Nombre:') !!}
                <p class="form-control form-control-static">{!! $school->name !!}</p>
            </div>

            <!-- Sector Field -->
            <div class="form-group col-md-6">
                {!! Form::label('sector', 'Sector:') !!}
                <p class="form-control form-control-static">{!! $school->sector !!}</p>
            </div>

            <!-- sub_sector Field -->
            <div class="form-group col-md-6">
                {!! Form::label('sub_sector', 'Sub sector:') !!}
                <p class="form-control form-control-static">{!! $school->sub_sector !!}</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-info">
        <h5 class="panel-heading">Ubicación</h5>
        <div class="panel-body">
            <!-- Province Field -->
            <div class="form-group col-md-6">
                {!! Form::label('province', 'Provincia:') !!}
                <p class="form-control form-control-static">{!! $school->province !!}</p>
            </div>

            <!-- Department Field -->
            <div class="form-group col-md-6">
                {!! Form::label('department', 'Partido:') !!}
                <p class="form-control form-control-static">{!! $school->department !!}</p>
            </div>

            <!-- City Field -->
            <div class="form-group col-md-6">
                {!! Form::label('city', 'Ciudad:') !!}
                <p class="form-control form-control-static">{!! $school->city !!}</p>
            </div>

            <!-- City Code Field -->
            <div class="form-group col-md-6">
                {!! Form::label('city_code', 'Código de ciudad:') !!}
                <p class="form-control form-control-static">{!! $school->city_code !!}</p>
            </div>

            <!-- Postal Code Field -->
            <div class="form-group col-md-6">
                {!! Form::label('postal_code', 'Código postal:') !!}
                <p class="form-control form-control-static">{!! $school->postal_code !!}</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-info">
        <h5 class="panel-heading">Contacto</h5>
        <div class="panel-body">
            <!-- Phone Area Field -->
            <div class="form-group col-md-6">
                {!! Form::label('phone_area', 'Area:') !!}
                <p class="form-control form-control-static">{!! $school->phone_area !!}</p>
            </div>

            <!-- Phone Area Field -->
            <div class="form-group col-md-6">
                {!! Form::label('phone_number', 'Número:') !!}
                <p class="form-control form-control-static">{!! $school->phone_number !!}</p>
            </div>

            <!-- Phone Area Field -->
            <div class="form-group col-md-6">
                {!! Form::label('email', 'Email:') !!}
                <p class="form-control form-control-static">{!! $school->email !!}</p>
            </div>
        </div>
    </div>
</div>