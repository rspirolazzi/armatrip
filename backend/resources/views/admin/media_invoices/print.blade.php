@extends('layouts.print_pdf')
@section('css')
    <style>
        body {
            font-size: 0.89em;
        }

        /*.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12,
        .col-xs-1:last-product, .col-xs-2:last-product, .col-xs-3:last-product, .col-xs-4:last-product, .col-xs-5:last-product, .col-xs-6:last-product, .col-xs-7:last-product, .col-xs-8:last-product, .col-xs-9:last-product, .col-xs-10:last-product, .col-xs-11:last-product, .col-xs-12:last-product {
            padding-right: 5px;
            padding-left: 5px;
        }*/
    </style>
@endsection
@section('header')
    <div class="row">
        <div class="col-xs-8 col-md-6">
            <dl class="dl-horizontal">
                <dt>Sucursal:</dt>
                <dd>{!! $media_invoice->store->name !!}</dd>
                <dt>Contacto:</dt>
                <dd>{!! $media_invoice->user->email !!}</dd>
                <dt>Tel:</dt>
                <dd>{!! $media_invoice->store->telephone !!}</dd>
                <dt>Dirección:</dt>
                <dd>{!! $media_invoice->store->address !!}, {!! $media_invoice->store->city !!}
                    - {!! $media_invoice->store->state !!}</dd>
            </dl>
        </div>
        <div class="col-xs-4 col-md-6">
            <h4 class="text-uppercase">
                Orden de Pedido <br/>
                N°:<b>{!! $media_invoice->order_number !!}</b>
            </h4>
        </div>
    </div>
@endsection
@section('content')
    @include('adminlte-templates::common.errors')
    <div class="row">
        @include('admin.media_invoices.print_fields')
    </div>
@endsection