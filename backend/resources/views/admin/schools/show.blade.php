@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Colegio
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.schools.show_fields')
                    <div class="clearfix"></div>
                    <a href="{!! route('admin.schools.index') !!}" class="btn btn-default">Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
