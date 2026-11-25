<div class="form-group col-sm-12">
    {!! Form::label('id', 'Código:') !!}
    <p class="form-control form-control-static">{!! $product->id !!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Categoría:') !!}
    <p class="form-control form-control-static">{!! isset($product->category) ? $product->category->name :'N/A'!!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Precio:') !!}
    <p class="form-control form-control-static">{!! $product->price !!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $product->name !!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('image', 'Imagen:') !!}
    <p class="form-control form-control-static">{!! $product->image !!}</p>
</div>

<div class="clearfix"></div>
<div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group col-sm-12 col-lg-12">
                {!! Form::label('description', 'Descripción:') !!}
                <p class="form-control form-control-static">{!! $product->description !!}</p>
            </div>

        </div>
        {{--<div class="col-sm-6">
            <div class="row">
                <div class="form-group col-sm-5">
                    {!! Form::label('min', 'Cant. mínima de gustos:') !!}
                    <p class="form-control form-control-static">{!! $product->min !!}</p>
                </div>

                <div class="form-group col-sm-5">
                    {!! Form::label('max', 'Cant. máxima de gustos:') !!}
                    <p class="form-control form-control-static">{!! $product->max !!}</p>
                </div>
            </div>
        </div>--}}
    </div>
</div>
<div class="clearfix"></div>

<div class="form-group col-sm-6">
    {!! Form::label('updated_at', 'Última actualización:') !!}
    <p class="form-control form-control-static"> {!! $product->updated_at !!}</p>
</div>
<div class="clearfix"></div>
