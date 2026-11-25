<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('id', '#:') !!}
    <p class="form-control form-control-static">{!! $packageGroup->id!!}</p>
</div>
<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $packageGroup->name!!}</p>
</div>
<!-- Code Field -->
<div class="form-group col-sm-4">
    {!! Form::label('code', 'Código:') !!}
    <p class="form-control form-control-static">{!! $packageGroup->code!!}</p>
</div>
<div class="clearfix"></div>
@if($packageGroup->styles)
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">Colores para distinguir los grupos</div>
            <div class="box-body">
                <div class="form-group col-sm-12">
                    {!! Form::label('bg_color', 'Estilo:') !!}
                    <div class="row">
                        <div class="col-md-3">
                            <label class="alert"
                                   style="width:100%;border:{!! $packageGroup->styles['color'] !!} solid 1px ;background-color:{!! $packageGroup->styles['backgroundColor'] !!};color:{!! $packageGroup->styles['color'] !!}">
                                {!! $packageGroup->styles['name']!!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="col-md-12">
    <div class="box">
        <div class="box-header">Multimedia del paquete</div>
        <div class="box-body">
            <div class="form-group col-sm-6">
                @if(isset($packageGroup) && $packageGroup->getFirstMedia(\App\Models\Admin\Package::$GROUP_IMAGE))
                    <label>Imagen de fondo (Desktop)</label>
                    <a href="{!! $packageGroup->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_IMAGE) !!}"
                       target="_blank" class="thumbnail">
                        <img src="{!! $packageGroup->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_IMAGE) !!}"
                             alt="{!! $packageGroup->name !!}">
                    </a>
                @endif
            </div>
            <div class="form-group col-sm-6">
                @if(isset($packageGroup) && $packageGroup->getFirstMedia(\App\Models\Admin\Package::$GROUP_MOBILE))
                    <label>Imagen de fondo (Mobile)</label>
                    <a href="{!! $packageGroup->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_MOBILE) !!}"
                       target="_blank" class="thumbnail">
                        <img src="{!! $packageGroup->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_MOBILE) !!}"
                             alt="{!! $packageGroup->name !!}">
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    <p class="form-control form-control-static">{!! $packageGroup->order!!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Activo:') !!}
    <p class="form-control form-control-static">
        @if($packageGroup->active)
            <i class="fa fa-check-square-o"></i>
        @else
            <i class="fa fa-square-o"></i>
        @endif
    </p>
</div>