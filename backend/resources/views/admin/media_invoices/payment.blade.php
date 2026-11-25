@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Proceder con el pago para el Pedido Interno @include('admin.media_invoices.partial.cart')
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="box box-warning with-border">
                            <div class="box-header">
                                <h3 class="box-title">Seleccione una forma de pago</h3>
                            </div>
                            <div class="box-body">
                                {!! Form::model($media_invoice, ['route' =>[ 'admin.media_invoices.selectPayment', $media_invoice->id,],  'method' => 'post']) !!}
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <input checked type="radio" name="payment" value="{!! \App\Models\Admin\MediaInvoice::$PAYMENT_CASH !!}" id="payment_{!! \App\Models\Admin\MediaInvoice::$PAYMENT_CASH !!}" />
                                        <label for="payment_{!! \App\Models\Admin\MediaInvoice::$PAYMENT_CASH !!}"><i class="fa fa-money"></i> Efectivo</label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="radio" name="payment" value="{!! \App\Models\Admin\MediaInvoice::$PAYMENT_TRANSFER !!}" id="payment_{!! \App\Models\Admin\MediaInvoice::$PAYMENT_TRANSFER !!}" />
                                        <label for="payment_{!! \App\Models\Admin\MediaInvoice::$PAYMENT_TRANSFER !!}"><i class="fa fa-exchange"></i> Transferencia bancaria</label>
                                        <h4>{!! \App\Models\Admin\Setting::get('payment_transfer', '') !!}</h4>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="radio" name="payment" value="{!! \App\Models\Admin\MediaInvoice::$PAYMENT_CARD !!}" id="payment_{!! \App\Models\Admin\MediaInvoice::$PAYMENT_CARD !!}" />
                                        <label for="payment_{!! \App\Models\Admin\MediaInvoice::$PAYMENT_CARD !!}"><i class="fa fa-credit-card"></i> Tarjeta de credito</label>
                                    </li>
                                </ul>
                                <button type="submit" class="btn btn-gradient btn-info btn-block">Continuar</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-7">
                        <div class="box box-info with-border">
                            <div class="box-header">
                                <h3 class="box-title">Resumen</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">Número de Pedido:<b>{!! $media_invoice->order_number !!}</b>
                                    </div>
                                    <div class="col-md-6">Tienda: <b>{!! $media_invoice->store->name !!}</b></div>
                                    <div class="col-md-6">Fecha:
                                        <b>{!! $media_invoice->created_at->format('d/m/Y') !!}</b></div>
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="col-xs-3 text-left">Producto</th>
                                        <th class="col-xs-3 text-left">Categoría</th>
                                        <th class="col-xs-2 text-center">Precio</th>
                                        <th class="col-xs-2 text-center">Cantidad</th>
                                        <th class="col-xs-2 text-right">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($media_invoice->items->sortBy('id') as $index => $item)
                                        <tr>
                                            <td class="text-left">@can('admin.media_invoices.category')<a
                                                        href="{!! route('admin.media_invoices.category' , [$item->model->category_id]) !!}#product_id_{!! $item->model->id !!}">{!! $item->model->name !!}</a>@endcan
                                            </td>
                                            <td class="text-left">
                                                {!! $item->model->category->name !!}
                                                @if($item->model->subFamily)
                                                    -> {!! $item->model->subFamily->name  !!}
                                                @endif
                                            </td>
                                            <td class="text-center">{!! App\Helpers\Helper::formatPrice($item->price) !!}
                                            <td class="text-center">{!! $item->qty !!}
                                            <td class="text-right">{!! App\Helpers\Helper::formatPrice($item->total) !!}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right">Total</td>
                                        <td class="text-right">{!!  App\Helpers\Helper::formatPrice($media_invoice->total) !!}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="text-right">
                    <a class="btn btn-gradient btn-default" href="{!! route('admin.media_invoices.index') !!}">Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <?php
    use App\Models\Admin\Setting;
    $mp_enabled_test = Setting::get('mp_enabled_test', env('MP_ENABLED_TEST'));

    if ($mp_enabled_test) {
        $mp_public_key = Setting::get('mp_public_key_test', config('armatrip.mercadopago.public_key'));
    } else {
        $mp_public_key = Setting::get('mp_public_key', config('armatrip.mercadopago.public_key'));
    }
    ?>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script>
        $(function () {
            Mercadopago.setPublishableKey("{!! $mp_public_key !!}");
            //Mercadopago.createToken(form, tokenHandler);
            //Mercadopago.getPaymentMethod(object, paymentMethodHandler);
            //Mercadopago.getIdentificationTypes(identificationHandler);
            Mercadopago.getIdentificationTypes();
            //Mercadopago.getIssuers(paymentMethodId, issuersHandler);
            //Mercadopago.getInstallments({"bin": bin,"amount": amount}, installmentHandler);

            $('input[data-checkout="cardNumber"]').on('blur keyup change', guessingPaymentMethod)


            function getBin() {
                var ccNumber = $('input[data-checkout="cardNumber"]')
                return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6)
            }

            function guessingPaymentMethod(event) {
                const bin = getBin()

                if (event.type === "keyup") {
                    if (bin.length >= 6) {
                        Mercadopago.getPaymentMethod({
                            "bin": bin
                        }, setPaymentMethodInfo)
                    }
                } else {
                    setTimeout(function () {
                        if (bin.length >= 6) {
                            Mercadopago.getPaymentMethod({
                                "bin": bin
                            }, setPaymentMethodInfo)
                        }
                    }, 100)
                }
            }


            function setPaymentMethodInfo(status, response) {
                if (status === 200) {
                    $("input[name=paymentMethodId]").val(response[0].id)
                }
            }
        })
    </script>
@endsection