<div class="form-group col-sm-12">
    {!! Form::label('id', '#:') !!}
    <p class="form-control form-control-static">{!! $package->id !!}</p>
</div>

<div class="form-group col-sm-8">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $package->name!!}</p>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('code', 'Código:') !!}
    <p class="form-control form-control-static">{!! $package->code!!}</p>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('package_group_id', 'Agrupar por:') !!}
    @if($package->package_group)
    <p class="form-control form-control-static">{!! $package->package_group->name!!}</p>
    @endif
</div>
<div class="form-group col-sm-8">
    {!! Form::label('tags', 'Tags:') !!}
    <p class="form-control form-control-static">{!! $package->tags!!}</p>
</div>
<div class="col-sm-12">

<div class="form-group">
    {!! Form::label('description', 'Descripción:') !!}
    <p class="form-control form-control-static">{!! $package->description!!}</p>
</div>
</div>
<div class="clearfix"></div>

<div class="form-group col-sm-6">
    {!! Form::label('store_id', 'Empresa:') !!}
    <p class="form-control form-control-static">{!! $package->store->name !!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('price', 'Precio:') !!}
    <div class="input-group">
        <span class="input-group-addon">$</span>
        <p class="form-control form-control-static">{!! $package->price!!}</p>
    </div>
</div>

<div class="form-group col-sm-6">
{!! Form::label('increment_percent', 'Incremento:') !!}
<div class="input-group">
    <p class="form-control form-control-static">{!! $package->increment_percent!!}</p>
    <span class="input-group-addon">%</span>
</div>
<p class="text-muted">Incremento al precio base que se aplicara</p>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">Configurar cuotas e intereses del paquete</div>
        <div class="box-body">
            <div class="form-group col-sm-6">
                {!! Form::label('installment', 'Cuotas:') !!}
                <p class="form-control form-control-static">{!! $package->installment!!}</p>
                {!! Form::select('installment', [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20,21=>21,22=>22,23=>23,24=>24], null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::label('tax', 'Tasa de interes (%) por cuota:') !!}
                <p class="form-control form-control-static">{!! $package->tax!!}</p>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="box-header">Multimedia del paquete</div>
        <div class="box-body">
            <div class="form-group col-sm-6">
                @if(isset($package) && $package->getFirstMedia(\App\Models\Admin\Package::$GROUP_IMAGE))
                    <label>Imagen de fondo (Desktop)</label>
                    <a href="{!! $package->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_IMAGE) !!}" target="_blank" class="thumbnail">
                        <img src="{!! $package->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_IMAGE) !!}" alt="{!! $package->name !!}">
                    </a>
                @endif
            </div>
            <div class="form-group col-sm-6">
                @if(isset($package) && $package->getFirstMedia(\App\Models\Admin\Package::$GROUP_MOBILE))
                    <label>Imagen de fondo (Mobile)</label>
                    <a href="{!! $package->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_MOBILE) !!}" target="_blank" class="thumbnail">
                        <img src="{!! $package->getFirstMediaUrl(\App\Models\Admin\Package::$GROUP_MOBILE) !!}" alt="{!! $package->name !!}">
                    </a>
                @endif
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('video_link', 'Video:') !!}
                <p class="form-control form-control-static">{!! $package->video_link!!}</p>
            </div>
        </div>
    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    <p class="form-control form-control-static">{!! $package->order!!}</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Activo:') !!}
    <p class="form-control form-control-static">
    @if($package->active)
        <i class="fa fa-check-square-o"></i>
    @else
        <i class="fa fa-square-o"></i>
    @endif
    </p>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
    <div class="box box-danger">
        <h5 class="box-header">Productos del paquete</h5>
        <div class="box-body">
            <table class="table table-striped">
                <tr>
                    <th class="hidden-xs">#</th>
                    <th class="hidden-xs">Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Default</th>
                </tr>
                @foreach($package->products as $product)
                    <tr>
                        <td class="hidden-xs"><a href="{!! route('admin.products.show', $product->id) !!}">{!! $product->id !!}</a></td>
                        <td class="hidden-xs"><a href="{!! route('admin.products.show', $product->id) !!}">{!! $product->code!!}</a></td>
                        <td><a href="{!! route('admin.products.show', $product->id) !!}">{!! $product->name !!}</a></td>
                        <td>
                            {!! $product->category()->withTrashed()->first()->name !!}
                        </td>
                        <td>{!! Form::faCheck($product->default) !!}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="form-group col-sm-6">
    {!! Form::label('updated_at', 'Última actualización:') !!}
    <p class="form-control form-control-static"> {!! $package->updated_at !!}</p>
</div>
<div class="clearfix"></div>
