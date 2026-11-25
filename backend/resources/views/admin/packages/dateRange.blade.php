@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Administrar fechas para : {{$package->name}} - {{$package->store->name}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <?php
        $years = [
        ];
        for ($i = 0; $i < 20; $i++) {
            $years[2019 + $i] = 2019 + $i;
        }
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Novimebre',
            12 => 'Diciembre',
        ];
        $biweeklys = [1 => 'Primera', 2 => 'Segunda'];

        ?>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($package, ['route' => ['admin.packages.updateDateRange', $package->id], 'method' => 'patch']) !!}
                    <table class="table table-striped">
                        <tr>
                            <th>Nombre</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Recargo (%) / Descuento(-%)</th>
                            <th>Eliminar?</th>
                        </tr>
                        <tr id="new">
                            <td>{{ Form::text('dateRange[0][name]', null, ['class'=>'form-control', 'disabled'=>true])}}</td>
                            <td>{{ Form::text('dateRange[0][date_start]',null, ['class'=>'form-control', 'disabled'=>true, 'placeholder'=>'', 'data-toggle'=>'range_datepicker', 'data-type'=>'start', 'data-range'=>'0'])}}</td>
                            <td>{{ Form::text('dateRange[0][date_end]',null, ['class'=>'form-control', 'disabled'=>true, 'placeholder'=>'', 'data-toggle'=>'range_datepicker', 'data-type'=>'end', 'data-range'=>'0'])}}</td>
                            <td>{{ Form::number('dateRange[0][tax]', 0, ['class'=>'form-control', 'disabled'=>true])}}</td>
                            <td>
                                <button type="button" onclick="onClickDisabled()" class="btn btn-default" value="new">
                                    Agregar
                                </button>
                            </td>
                        </tr>
                        @foreach($package->dateRanges as $dateRange)
                            <tr>
                                <td>{{ Form::text('dateRange['.$dateRange->id.'][name]', $dateRange->name, ['class'=>'form-control'])}}</td>
                                <td>{{ Form::text('dateRange['.$dateRange->id.'][date_start]',isset($dateRange->date_start) ?$dateRange->date_start->format('d/m/Y'):null, ['class'=>'form-control', 'placeholder'=>'', 'data-toggle'=>'range_datepicker', 'data-type'=>'start', 'data-range'=>$dateRange->id])}}</td>
                                <td>{{ Form::text('dateRange['.$dateRange->id.'][date_end]',isset($dateRange->date_end) ?$dateRange->date_end->format('d/m/Y'):null, ['class'=>'form-control', 'placeholder'=>'', 'data-toggle'=>'range_datepicker', 'data-type'=>'end', 'data-range'=>$dateRange->id])}}</td>
                                <td>{{ Form::number('dateRange['.$dateRange->id.'][tax]', $dateRange->tax, ['class'=>'form-control'])}}</td>
                                <td>{{ Form::checkbox('dateRange['.$dateRange->id.'][delete]', $dateRange->id) }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                        <a href="{!! route('admin.packages.edit', [$package->id]) !!}"
                           class="btn btn-default btn-gradient">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            debugger
            $('[data-toggle="range_datepicker"][data-type="start"]').each(function (i, e) {
                setRangeDatePicker(e)
            })

            function setRangeDatePicker(element) {
                var dateFormat = "dd/mm/yy",
                    from = $(element)
                        .datepicker({
                            defaultDate: "+1w",
                            dateFormat: dateFormat,
                            changeYear: true,
                            changeMonth: true
                        })
                        .on("change", function () {
                            to.datepicker("option", "minDate", getDate(this));
                        }),
                    index = $(element).data('range'),
                    to = $('[data-toggle="range_datepicker"][data-type="end"][data-range=' + index + ']').datepicker({
                        defaultDate: "+1w",
                        dateFormat: dateFormat,
                        changeYear: true,
                        changeMonth: true,
                    })
                        .on("change", function () {
                            from.datepicker("option", "maxDate", getDate(this));
                        });

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }

                    return date;
                }
            }
        });
    </script>
    <script type="text/javascript">


        var onClickDisabled = function () {
            var disabled = jQuery('#new input,#new select').eq(0).prop('disabled')
            jQuery('#new input,#new select').each(function (i, e) {
                $(e).prop('disabled', !disabled);
                if (disabled) {
                    jQuery('#new button').html('Cancelar')
                } else {
                    jQuery('#new button').html('Agregar')
                }
            });
        };
    </script>
@endsection
