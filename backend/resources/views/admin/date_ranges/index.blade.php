@extends('layouts.app')

@section('content')
    @include('admin.date_ranges.actions')
    <section class="content-header">
        <h1 class="pull-left">Date Ranges</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.date_ranges.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

