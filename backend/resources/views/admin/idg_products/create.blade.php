@extends('layouts.app')

@section('content')
    @include('admin.idg_products.actions')
    <section class="content-header">
        <h1>
            Alta producto
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                {!! Form::open(['route' => 'idg.products.store', 'method' => 'post', 'files'=>'true']) !!}
                @include('admin.idg_products.fields')
                <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Agregar Producto', ['class' => 'btn btn-primary btn-gradient']) !!}
                        <a href="{!! route('idg.products.index') !!}" class="btn btn-default btn-gradient">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
