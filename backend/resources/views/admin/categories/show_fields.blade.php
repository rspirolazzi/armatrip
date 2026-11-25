<div class="form-group col-sm-12">
    {!! Form::label('id', '#:') !!}
    <p class="form-control form-control-static">{!! $category->id!!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    <p class="form-control form-control-static">{!! $category->code!!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-control-static">{!! $category->name!!}</p>
</div>


<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    <p class="form-control form-control-static">{!! $category->order!!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('active', 'Activo?:') !!}
    <p class="form-control ">{!! Form::faCheck($category->active)!!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('required', 'Requerido?:') !!}
    <p class="form-control ">{!! Form::faCheck($category->required)!!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('multiple', 'Multiple selección?:') !!}
    <p class="form-control ">{!! Form::faCheck($category->multiple)!!}</p>
</div>

<div class="clearfix"></div>

<div class="form-group col-sm-6">
    {!! Form::label('updated_at', 'Última actualización:') !!}
    <p class="form-control form-control-static"> {!! $category->updated_at !!}</p>
</div>
<div class="clearfix"></div>
