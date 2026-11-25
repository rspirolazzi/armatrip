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
                        @include('admin.media_invoices.fields')
                </div>
            </div>
        </div>
    </div>
@endsection
