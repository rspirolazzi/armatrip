@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Cupónes</h1>
        <h1 class="pull-right">
           @can('admin.coupons.create')<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.coupons.create') !!}">Nuevo Cupón</a>@endcan
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @filter(['route'=>'admin.coupons.index'])
                    <div class="col-md-3 col-xs-12">
                        {!! Form::text('code', Request::get('code'), ['class' => 'form-control', 'placeholder'=>'Buscar por código']) !!}
                    </div>
                    <div class="col-md-3 col-xs-12">
                        {!! Form::text('name', Request::get('name'), ['class' => 'form-control', 'placeholder'=>'Buscar por nombre']) !!}
                    </div>
                    <div class="col-md-3 col-xs-12">
                        {!! Form::text('description', Request::get('description'), ['class' => 'form-control', 'placeholder'=>'Buscar por descripción']) !!}
                    </div>
                @endfilter
                @include('admin.coupons.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $coupons])
        </div>
    </div>
@endsection

