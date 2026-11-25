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
                <div class="row">
                    @include('admin.media_invoices.show_fields')
                </div>
                <a class="btn btn-gradient btn-default btn-block" href="{!! route('admin.media_invoices.index') !!}">Volver</a>
            </div>
        </div>
    </div>
@endsection
