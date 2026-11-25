@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pago con tarjeta de crédito para el Pedido Interno @include('admin.media_invoices.partial.cart')
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <div class="box box-warning with-border">
                            <div class="box-header">
                                <h3 class="box-title">Datos de la tarrjeta</h3>
                            </div>
                            <div class="box-body">
                                <div class="overlay"
                                     style="display:none;z-index: 99999;position: absolute;width: 100%;height: 100%;">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                                {!! Form::model($media_invoice, ['route' =>[ 'admin.media_invoices.cardPayment', $media_invoice->id,],  'method' => 'post', 'id'=>'pay']) !!}
                                <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('cardNumber', 'Número de Tarjeta') !!}
                                                {!! Form::number('cardNumber', null, ['placeholder'=>"4509 9535 6623 3704",'maxLength'=>16,'data-checkout'=>"cardNumber",'id'=>"cardNumber",'class'=>"form-control",]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('cardholderName', 'Titular') !!}
                                                {!! Form::text('cardholderName', null,['placeholder'=>"Nombre del titular como figura en la tarjeta", 'data-checkout'=>"cardholderName",'id'=>"cardholderName",'class'=>"form-control",]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('docType', 'Documento') !!}
                                                {!! Form::select('docType', ['DNI'=>'DNI'], null, ['placeholder'=>"Tipo de documento", 'data-checkout'=>"docType",'id'=>"docType",'class'=>"form-control",]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('docNumber', 'Número') !!}
                                                {!! Form::number('docNumber', null,['placeholder'=>"Número de documento", 'data-checkout'=>"docNumber",'id'=>"docNumber",'class'=>"form-control",]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label("cardExpirationMonth","Mes (Expiración)") !!}
                                            {!! Form::number('cardExpirationMonth', null, ['placeholder'=>"MM", 'data-checkout'=>"cardExpirationMonth",'id'=>"cardExpirationMonth" ,'class'=>"form-control", 'maxLength'=>2,]) !!}</div>
                                        <div class="col-md-4">
                                            {!! Form::label("cardExpirationYear","Año (Expiración)") !!}
                                            {!! Form::number('cardExpirationYear', null, ['placeholder'=>"AAAA", 'data-checkout'=>"cardExpirationYear",'id'=>"cardExpirationYear" ,'class'=>"form-control", 'maxLength'=>4]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('securityCode', 'Cód. de seg.') !!}
                                            {!! Form::number('securityCode', null, ['placeholder'=>"Código de seguridad", 'maxLength'=>4,'data-checkout'=>"securityCode",'id'=>"securityCode",'class'=>"form-control",]) !!}
                                        </div>
                                        <div class="col-md-12">
                                            <hr/>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-gradient btn-success btn-block">Pagar</button>
                                        </div>
                                    </div>
                                {!! Form::hidden("payment_method_id") !!}
                                {!! Form::hidden("mp_notification_id") !!}
                                {!! Form::close() !!}
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
    //dd($mp_public_key);
    ?>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script>
        $(function () {
                Mercadopago.setPublishableKey("{!! $mp_public_key !!}");
                Mercadopago.getIdentificationTypes();

                $('input[data-checkout="cardNumber"]').on('blur keyup change', guessingPaymentMethod)


                function getBin() {
                    var ccNumber = $('input[data-checkout="cardNumber"]').val()
                    return ccNumber.replace(/[ .-]/g, '').slice(0, 6)
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

                function sdkResponseHandler(status, response) {
                    console.log(status, response)
                    if (status !== 200 && status !== 201) {
                        $('.overlay').hide();
                        alert("Ocurrio un error/es");
                        $('input[name=mp_notification_id]').val('')
                    } else {
                        $('input[name=mp_notification_id]').val(response.id)
                        $('#pay').submit();
                    }
                }

                $('#pay').on('submit', function(e){
                    if ($('input[name=mp_notification_id]').val() == '') {
                        e.preventDefault();
                        $('.overlay').show();
                        var $form = document.querySelector('#pay')
                        //Comenzar isLoading
                        Mercadopago.createToken($form, sdkResponseHandler)
                    }
                })

                function setPaymentMethodInfo(status, response) {
                    if (status === 200) {
                        $("input[name=payment_method_id]").val(response[0].id)
                    }
                }
            }
        )
    </script>
@endsection