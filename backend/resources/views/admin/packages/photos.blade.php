@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Administrar fotos : {{$package->name}} - {{$package->store->name}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($package, ['route' => ['admin.packages.uploadPhotos', $package->id], 'method' => 'post', 'class'=>'dropzone', 'files'=>true, 'id'=>'frmUpload']) !!}
                    <div class="form-group-sm col-md-12">
                        <label class="col-md-2">Subir para:</label>
                        <div class="col-md-3">

                            {!! Form::select('upload_for',['both'=>'Desktop y Mobile', 'desktop'=>'Solo Deskop', 'mobile'=>'Solo Mobile'], null, ['class'=>'form-control input-sm']) !!}
                        </div>
                    </div>
                    <div class="fallback">
                        {!! Form::file('file', null, ['multiple'=>'multiple']) !!}
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <hr/>
                    {!! Form::model($package, ['route' => ['admin.packages.updatePhotos', $package->id], 'method' => 'patch']) !!}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-default active" onclick="filter('both')">
                                <input onclick="filter('both')" type="radio" name="options" id="both" autocomplete="off" checked> Ver desktop y mobile
                            </label>
                            <label class="btn btn-default" onclick="filter('{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA !!}')">
                                <input onclick="filter('{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA !!}')" type="radio" name="options" id="{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA !!}" autocomplete="off"> Ver desktop
                            </label>
                            <label class="btn btn-default" onclick="filter('{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA_MOBILE !!}')">
                                <input onclick="filter('{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA_MOBILE !!}')" type="radio" name="options" id="{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA_MOBILE !!}" autocomplete="off"> Ver mobile
                            </label>
                        </div>
                        <hr/>
                    </div>
                    <div id="multimedia" class="col-xs-12">
                        @foreach($package->media as $index=>$media)
                            <div class="col-xs-6 col-sm-3"
                                 data-filter-group="{!! $media->pivot->group !!}">
                                <div class="thumbnail">
                                    <a target="_blank" href="{!! $media->getUrl() !!}"><img
                                                src="{!! $media->getUrl() !!}"
                                                alt="{!! $media->name !!}"
                                                style="height: 100px"></a>
                                    <div class="caption">
                                        <div class="form-group">
                                            <label class="control-label">
                                                <small>Eliminar imagen?</small>
                                                {!! Form::checkbox('delete[]', $media->id, null, ['tabindex'=>$index+1*10]) !!}
                                            </label>
                                            {!! Form::text('media_name['.$media->id.']', $media->name, ['class'=>'form-control input-sm', 'tabindex'=>$index+1*20]) !!}
                                        </div>
                                        @if($media->pivot->group === App\Models\Admin\Package::$GROUP_MULTIMEDIA)
                                            <small><i class="fa fa-desktop"></i> Versión para Desktop</small>
                                        @elseif($media->pivot->group === App\Models\Admin\Package::$GROUP_MULTIMEDIA_MOBILE)
                                            <small><i class="fa fa-mobile"></i> Versión para Mobile</small>
                                        @endif
                                        <br/>
                                        <small><i class="fa fa-calendar"></i> {!! $media->created_at->format('d-m-Y H:i') !!}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="btn-group col-sm-12">
                        {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                        <a href="{!! route('admin.packages.edit', [$package->id]) !!}"
                           class="btn btn-default btn-gradient">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function filter(type) {
            switch (type) {
                case '{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA !!}':
                case '{!! App\Models\Admin\Package::$GROUP_MULTIMEDIA_MOBILE !!}':
                    $('#multimedia > div').each(function(i,e){
                        if($(e).data('filter-group') != type){
                            $(e).fadeOut(500, function(){
                                $(e).hide();
                            })
                        }else{
                            $(e).fadeIn(500, function(){
                                $(e).show();
                            })
                        }
                    });
                    break
                default:
                    $('#multimedia > div').each(function(i,e){
                        $(e).fadeIn(500, function(){
                            $(e).show();
                        })
                    })
                    break
            }
        }

        Dropzone.options.frmUpload = {
            init: function () {
                this.on("completemultiple", function (file) {
                    debugger
                    console.log("completemultiple.", file);
                });
            }
        };
    </script>
@endsection
