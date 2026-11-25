@extends('layouts.app')

@section('content')
    @include('admin.notifications.actions')
    <section class="content-header">
        <h1 class="pull-left">Mensajes</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @filter(['route'=>'admin.notifications.index', 'placeholder'=>'Buscar por nombre'])
                    <div class="col-md-9 col-xs-8">
                        {!! Form::text('title', Request::get('title'), ['class' => 'form-control', 'placeholder'=>'Buscar por Titulo']) !!}
                    </div>
                @endfilter
                @include('admin.notifications.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $notifications])
        </div>
    </div>
@endsection

