@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Revisá tu pedido antes de confirmar
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($invoice, ['route' => ['admin.invoices.confirm_invoice', $invoice->id], 'method' => 'patch']) !!}
                    @include('admin.invoices.show_fields')
                    <div class="form-group">
                        <label>Ingresa un comentario (opcional)</label>
                        {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::submit('Confirmar pedido', ['class' => 'btn btn-success btn-block']) !!}
                    <br/>
                    <p class="text-center">@can('admin.invoices.edit')<a href="{!! route('admin.invoices.edit', $invoice->id) !!}">Modificar</a>@endcan</p>
                    <p class="text-center">@can('admin.invoices.index')<a href="{!! route('admin.invoices.index') !!}">Volver</a>@endcan</p>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
