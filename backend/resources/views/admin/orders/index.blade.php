@extends('layouts.app')

@section('scripts')
    <script>
        $(function () {
            var timeOut = null, interval = null, valueForReloadInSeconds = parseInt('{!! $valueInSeconds !!}', 0)

            function reloadAdmin() {
                window.location.reload();
            }

            function setTimeOutForReload() {
                window.localStorage.setItem('reload', 'true')
                $('#reload button')
                    .removeClass('btn-default')
                    .addClass('btn-warning')
                    .data('checked', true)
                $('#reload span.text-left').text('Recargando automaticamente en ')
                $('#reload #countdown-number').show()
                $('#reload i')
                    .addClass('fa')
                    .addClass('fa-circle-o-notch')
                    .addClass('fa-spin')
                    //.addClass('fa-3x')
                    .addClass('fa-fw')

                startCountdown()
                timeOut = setTimeout(reloadAdmin, valueForReloadInSeconds * 1000)
            }

            function cleanTimeOutForReload() {
                window.localStorage.removeItem('reload')
                $('#reload button')
                    .addClass('btn-default')
                    .removeClass('btn-warning')
                    .data('checked', false)
                $('#reload span.text-left').text('Click para recargar automaticamente')
                $('#reload #countdown-number').hide()
                $('#reload i')
                    .removeClass('fa')
                    .removeClass('fa-circle-o-notch')
                    .removeClass('fa-spin')
                    //.removeClass('fa-3x')
                    .removeClass('fa-fw')

                stopCountdown()
                clearTimeout(timeOut)
            }

            $('#reload button').on('click', function (e) {
                $(this).data('checked', !$(this).data('checked'))
                if ($(this).data('checked')) {
                    setTimeOutForReload()
                } else {
                    cleanTimeOutForReload()
                }
            });

            function startCountdown() {
                var countdownNumberEl = document.getElementById('countdown-number');
                var countdown = valueForReloadInSeconds;
                countdownNumberEl.textContent = countdown;
                interval = setInterval(function () {
                    countdown = --countdown <= 0 ? valueForReloadInSeconds : countdown;

                    if (countdown == 1) {
                        clearInterval(interval)
                    }
                    countdownNumberEl.textContent = countdown;
                }, 1000);
            }

            function stopCountdown() {
                clearInterval(interval)
            }


            if (window.localStorage.getItem('reload') != null) {
                setTimeOutForReload()
            } else {
                cleanTimeOutForReload()
            }
        })
    </script>
@endsection
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Pedidos</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.orders.filter')
                @include('admin.orders.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $orders])
        </div>
    </div>
@endsection

