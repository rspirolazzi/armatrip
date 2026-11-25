@section('scripts')
    <script>
        $(function(){
            $('#frmCoupons').submit(function(e){

            })
        })
    </script>
@endsection
<div>
    {!! Form::open(['id'=>'frmCoupons','route' => 'admin.coupons.index', 'method'=>'GET']) !!}
    <div class="row">
            <div class="col-md-11">
                {!! Form::text('search', Request::get('search'), ['class' => 'form-control', 'placeholder'=>'Buscá cupones por código, nombre o descripción']) !!}
            </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<hr/>