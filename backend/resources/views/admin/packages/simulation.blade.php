@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Simulación de Paquete: {{$package->name}}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="col-md-12">
                        {!! Form::open(['route' => ['admin.packages.simulation', $package->id], 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('province', 'Provincia', ['class' => 'control-label']) !!}
                            {!! Form::select('province', \App\Models\Admin\Province::pluck('name', 'code') , null , ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('qty', 'Cantidad', ['class' => 'control-label']) !!}
                            {!! Form::number('qty', null , ['class' => 'form-control']) !!}
                        </div>
                        <button type="button" class="btn btn-primary ">Simular</button>
                        <a href="{!! route('admin.packages.index') !!}" class="btn btn-default btn-gradient"> Volver</a>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
