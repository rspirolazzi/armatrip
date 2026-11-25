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
                    {!! Form::open(['route' => 'admin.media_invoices.update_mass_item', 'method' => 'post']) !!}
                    @include('admin.media_invoices.confirm_fields')
                    {!! Form::hidden('redirect_to', route('admin.media_invoices.confirm', $media_invoice->id)) !!}
                    <p class="text-danger"><i class="fa fa-info-circle"></i> Para quitar productos, ingresa la cantidad 0 y presione actualizar</p>
                    <button class="btn btn-gradient btn-success" type="submit" name="action" value="update">Actualizar</button>
                    <button class="btn btn-gradient btn-info" type="submit" name="action" value="update_continue">Confirmar y pagar</button>
                    <a class="btn btn-gradient" href="{!! route('admin.media_invoices.create') !!}">Seguir comprando</a>
                    <button class="btn btn-gradient btn-danger" type="submit" name="action" value="empty">Vaciar carrito</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
