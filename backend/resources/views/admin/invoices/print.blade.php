@extends('layouts.print_pdf')
@section('css')
    <style>
        .form-control-signature{
            border-top: 0px;
            border-radius: 0;
            border-left: 0;
            border-right: 0;
            box-shadow: none;
        }
    </style>
@endsection
@section('header')
    <div class="row">
        <div class="col-xs-8 col-md-6">
            <div class="row">
                <div class="col-xs-2">Sucursal:</div>
                <div class="col-xs-10"><b>{!! $invoice->store->name !!}</b></div>
                <div class="col-xs-2">Contacto:</div>
                <div class="col-xs-10"><b>{!! $invoice->user->email !!}</b></div>
                <div class="col-xs-2">Tel:</div>
                <div class="col-xs-10"><b>{!! $invoice->store->telephone !!}</b></div>
                <div class="col-xs-2">Dirección:</div>
                <div class="col-xs-10"><b>{!! $invoice->store->address !!}, {!! $invoice->store->city !!}
                        - {!! $invoice->store->state !!}</b></div>
            </div>
        </div>
        <div class="col-xs-4 col-md-6">
            <h4 class="text-uppercase">
                Orden de Pedido <br/>
                N°:<b>{!! $invoice->order_number !!}</b>
            </h4>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        @include('admin.invoices.print_fields')
    </div>
@endsection