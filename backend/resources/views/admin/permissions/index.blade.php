@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Permisos</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="text-right">

                    {!! Form::open(['route' => 'admin.permissions.regenerate', 'method' => 'post']) !!}
                    <button type="submit" class="btn btn-info">Regenerar permisos</button>
                    {!! Form::close() !!}
                </div>
                @include('admin.permissions.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

