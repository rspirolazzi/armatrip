@section('scripts')
    <script>
        $(function(){
            $('#frmCoupons').submit(function(e){

            })
        })
    </script>
@endsection
<div>
    {!! Form::open(['id'=>'frmCoupons','route' => 'admin.coupons.usersIndex', 'method'=>'GET']) !!}
    <div class="row">
            <div class="col-md-11">
                {!! Form::text('search', Request::get('search'), ['class' => 'form-control', 'placeholder'=>'Buscá usuarios por email, nombre o código de cupón']) !!}
            </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<hr/>