<!-- Nombre Field -->
<div class="form-group col-sm-12">
    {!! Form::label('id', '#:') !!}
    <p class="form-control form-control-static">{!! $brand->id !!}</p>
</div>
<!-- Nombre Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $brand->name !!}</p>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('code', 'Código:') !!}
    <p class="form-control form-control-static">{!! $brand->code !!}</p>
    <span class="help-block">Se genera automaticamente</span>
</div>
<!-- Descripción Field -->
<div class="form-group col-sm-12">
    {!! Form::label('description', 'Descripción:') !!}
    <p class="form-control form-control-static">{!! $brand->description !!}</p>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="box-header">Multimedia del paquete</div>
        <div class="box-body">
            <div class="form-group col-sm-6">
                @if(isset($brand) && $brand->getFirstMedia(\App\Models\Admin\Brand::$GROUP_LOGO))
                    <a href="{!! $brand->getFirstMediaUrl(\App\Models\Admin\Brand::$GROUP_LOGO) !!}" target="_blank" class="thumbnail">
                        <img src="{!! $brand->getFirstMediaUrl(\App\Models\Admin\Brand::$GROUP_LOGO, \App\Models\Admin\Package::$IMAGE_CONVERSION_THUMBNAIL) !!}" alt="{!! $brand->name !!}" style="max-height: 200px">
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Orden Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    <p class="form-control form-control-static">{!! $brand->order!!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Activo:') !!}
    <p class="form-control form-control-static">
        @if($brand->active)
            <i class="fa fa-check-square-o"></i>
        @else
            <i class="fa fa-square-o"></i>
        @endif
    </p>
</div>
<div class="clearfix"></div>