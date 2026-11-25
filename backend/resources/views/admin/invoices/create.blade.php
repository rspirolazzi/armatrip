@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pedido Interno
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.invoices.store']) !!}
                        @include('admin.invoices.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
