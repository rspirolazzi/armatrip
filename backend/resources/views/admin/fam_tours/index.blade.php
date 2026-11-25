@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Fam Tour</h1>
        <div class="pull-right">
            @if(Request::get('sync'))
            {!! Form::open(['route' => ['admin.fam_tours.sync'], 'method' => 'post']) !!}
            <button type="submit" class="btn btn-success" onclick="syncEmails(this)">
                <i class="fa fa-refresh"></i> Sincronzar emails 
            </button>
            <p id="syncWarning" class="alert alert-warning hide"><i class="fa fa-warning"></i> Este proceso puede demorar varios minutos, por favor espere</p>
            {!!Form::close()!!}
            @endif
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.fam_tours.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $fam_tours])
        </div>
    </div>
    <script type="text/javascript">
        function syncEmails(thiz){
            $(thiz).find('i')
            .attr('disabled', true)
            .addClass('fa-spin');
            $('#syncWarning').removeClass('hide')
        }
    </script>
@endsection