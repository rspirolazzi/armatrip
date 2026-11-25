@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Mercado Pago
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.mercado_pagos.show_fields')
                    <div class="col-md-12">
                        <hr/>
                    @can('admin.mercadoPagos.index')<a href="{!! route('admin.mercadoPagos.index') !!}" class="btn btn-default btn-gradient"> Volver</a>@endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
