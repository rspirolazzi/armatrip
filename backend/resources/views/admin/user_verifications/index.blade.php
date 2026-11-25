@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Verificaciones de usuarios</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.user_verifications.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

