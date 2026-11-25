@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Alta paquete
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.packages.store', 'files'=>'true']) !!}
                        @include('admin.packages.fields')
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Agregar Paquete', ['class' => 'btn btn-primary btn-gradient']) !!}
                            <a href="{!! route('admin.packages.index') !!}" class="btn btn-default btn-gradient">Cancelar</a>
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
