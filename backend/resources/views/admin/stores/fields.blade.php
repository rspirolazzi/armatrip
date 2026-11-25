<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Código:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'data-target'=>'input[name=name]', 'data-toggle'=>'autocomplete-code', 'maxlength'=>20]) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('telephone', 'Teléfono:') !!}
    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12 col-lg-6">
    {!! Form::label('tags', 'Tags:') !!}
    {!! Form::text('tags', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12 col-lg-6">
    {!! Form::label('icon', 'Imagen:') !!}
    {!! Form::file('icon', ['accept' => '.jpg,.jpeg,.png']) !!}
    <p class="help-block">JPG o PNG, hasta {!! floor(\Illuminate\Http\UploadedFile::getMaxFilesize() / 1024 / 1024) !!} MB.</p>
    @if(isset($store) && $store->getIconUrl())
        <?php $media = $store->getFirstMedia(\App\Models\Store::$GROUP_ICON); ?>
        <div class="thumbnail store-icon">
            <img src="{{ $store->getIconUrl() }}" alt="Imagen de {{ $store->name }}">
        </div>
        @if($media)
            {!! Form::hidden('icon_media_id', $media->id) !!}
        @endif
        <div class="checkbox">
            <label>
                {!! Form::checkbox($media ? '_delete_icon' : '_delete_icon_base64', $media ? $media->id : 1) !!} Eliminar imagen
            </label>
        </div>
    @endif
</div>
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('currency_id', 'Moneda:') !!}
    {!! Form::select('currency_id', \App\Models\Admin\Currency::pluck('name','id') , null , ['class' => 'form-control', 'placeholder'=>'']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('is_foreign', 0) !!}
    {!! Form::label('is_foreign', 'Es exterior?:') !!}
    {!! Form::checkbox('is_foreign', 1) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::hidden('active', 0) !!}
    {!! Form::label('active', 'Activo:') !!}
    {!! Form::checkbox('active', 1) !!}
</div>