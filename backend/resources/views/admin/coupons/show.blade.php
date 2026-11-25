@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cupón
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.coupons.show_fields')
                    @can('admin.coupons.index')<a href="{!! route('admin.coupons.index') !!}" class="btn btn-default">Volver</a>@endcan
                </div>
            </div>
        </div>
    </div>
@endsection
