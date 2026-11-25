@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Categoría: {!! $category->name !!}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.idg_categories.show_fields')
                    <a href="{!! route('idg.categories.index') !!}" class="btn btn-default btn-gradient"> Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
