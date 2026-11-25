@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pedido Interno {!! $invoice->order_number !!}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($invoice, ['route' => ['admin.invoices.update', $invoice->id], 'method' => 'patch']) !!}
                    @include('admin.invoices.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection