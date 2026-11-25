<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code']) !!}
    <span class="help-block">Se genera automaticamente</span>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control',]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('price', 'Precio:') !!}
    <div class="input-group">
        <span class="input-group-addon">$</span>
        {!! Form::text('price', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Categoría:') !!}
    {!! Form::select('category_id', \App\Models\Admin\Category::orderBy('name', 'asc')->pluck('name', 'id'), null, ['class' => 'form-control', 'data-toggle'=>'select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('order', 'Orden:') !!}
    {!! Form::text('order', isset($order) ? $order : null , ['class' => 'form-control']) !!}
</div>
<div class="clearfix"></div>
<div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group col-sm-12 col-lg-12">
                {!! Form::label('description', 'Descripción:') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            </div>

        </div>
    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::hidden('active', 0) !!}
    {!! Form::label('active', 'Activo?:') !!}
    {!! Form::checkbox('active', 1) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('allowed_to_sell', 0) !!}
</div>
<div class="clearfix"></div>
