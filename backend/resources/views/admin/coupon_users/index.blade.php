@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Usuarios que utilizarón cupones</h1>
        <h1 class="pull-right">

        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.coupon_users.filter')
                    @include('admin.coupon_users.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $couponUsers])
        </div>
    </div>
@endsection

