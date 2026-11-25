<script type="javascript">
    $(function () {
        $('#frmFilter').submit(function (e) {

        })
    })
</script>
<div>
    <div class="row">
        <div class="col-md-11">
            {!! Form::open(['id'=>'frmFilter','route' => $route, 'method'=>'GET']) !!}
            <div class="row">
                <div class="col-md-11">
                    {!! Form::text('search', Request::get('search'), ['class' => 'form-control', 'placeholder'=>$placeholder]) !!}
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">Buscar <i class="fa fa-search"></i></button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-md-1">
            {!! Form::open(['id'=>'frmFilter','route' => $route, 'method'=>'GET']) !!}
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-danger" name="clean" value="1">Limpiar <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<hr/>