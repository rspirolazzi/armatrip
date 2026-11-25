@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Order
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.fam_tours.show_fields')
                    <a href="{!! route('admin.fam_tours.index') !!}" class="btn btn-default">Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
