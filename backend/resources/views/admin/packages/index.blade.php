@extends('layouts.app')

@section('content')
    @include('admin.packages.actions')
    <section class="content-header">
        <h1 class="pull-left">Administrar paquetes</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
            @filter(['route'=>'admin.packages.index', 'placeholder'=>'Buscar por nombre'])
                <div class="col-xs-5">
                    {!! Form::text('name', Request::get('name'), ['class' => 'form-control', 'placeholder'=>'Buscar por Nombre']) !!}
                </div>
                <div class="col-xs-5">
                    {!! Form::select('store_id', \App\Models\Store::pluck('name', 'id'), Request::get('store_id'), ['class' => 'form-control', 'placeholder'=>'Buscar por empresa', 'data-toggle'=>'select2']) !!}
                </div>
            @endfilter
            @include('admin.packages.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $packages])

        </div>
    </div>
@endsection