@extends('layouts.app')

@section('content')
    @include('admin.schools.actions')

    <section class="content-header">
        <h1 class="pull-left">Colegios</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @filter(['route'=>'admin.schools.index', 'placeholder'=>'Buscar por nombre'])
                    <div class="col-md-9 col-xs-8">
                        {!! Form::text('name', Request::get('name'), ['class' => 'form-control', 'placeholder'=>'Buscar por Nombre']) !!}
                    </div>
                @endfilter
                @include('admin.schools.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $schools])

        </div>
    </div>
@endsection

