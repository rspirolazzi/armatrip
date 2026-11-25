@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Pedidos Interno</h1>
        <h1 class="pull-right">
           @can('idg.media_invoices.create')<a class="btn btn-gradient btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('idg.media_invoices.create') !!}">Nuevo pedido</a>@endcan
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.media_invoices.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

