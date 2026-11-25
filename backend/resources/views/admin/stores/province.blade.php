@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agregar paises a la empresa: {!! $store->name !!}
        </h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($store, ['route' => ['admin.stores.provinceUpdate', $store->id], 'method' => 'patch']) !!}
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="_all"/></th>
                                <th>País</th>
                                {{--<th></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($provinces->sortBy('name') as $province)
                                <?php
                                        $value = $store->provinces->firstWhere('id', $province->id);
                                ?>
                                <tr>
                                    <td>{!! Form::checkbox('province_id[]', $province->id, isset($value) , ['id'=>'province_'. $province->id, 'class'=>'check_all' ]) !!}</td>
                                    <td><label for="province_{!! $province->id !!}">{!! $province->name !!}</label></td>
                                    {{--<td>
                                        <small>
                                        {!! $province->children->map(function($v){return $v->name;})->implode(', ') !!}
                                        </small>
                                    </td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                        <a href="{!! route('admin.stores.index') !!}" class="btn btn-default btn-gradient">Volver</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="application/javascript">
        $(function () {
            $('#_all').on('change', function (e) {
                $('.check_all').prop('checked', $('#_all:checked').length !== 0);
            });
            $('.check_all').on('change', function (e) {
                $('#_all').prop('checked', $('.check_all:checked').length === $('.check_all').length);
            });
        });
    </script>
@endsection