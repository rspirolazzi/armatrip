@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pedido Interno @include('admin.media_invoices.partial.cart')
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="box-header">
                    <h3 class="box-title text-center">{!! $category->name !!} </h3>
                    <small class="pull-right">@can('idg.media_invoices.create')<a href="{!! route('idg.media_invoices.create') !!}">VOLVER</a>@endcan</small>
                </div>
                <div class="panel-body">
                    @include('admin.media_invoices.product_fields')
                </div>
                <div class="panel-footer text-center">
                    @can('idg.media_invoices.create')<a class="btn btn-default btn-block" href="{!! route('idg.media_invoices.create') !!}">VOLVER</a>@endcan
                </div>
            </div>
        </div>
    </div>
@endsection
