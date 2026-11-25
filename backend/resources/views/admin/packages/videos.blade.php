@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Administrar videos : {{$package->name}} - {{$package->store->name}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')

        <div class="box box-primary">

            <div class="box-header">
                <a class="pull-right btn btn-primary {!! sizeof( $package->videos)>=6?'disabled':'' !!}" href="#"
                   role="button"
                   data-toggle="modal" data-target="#uploadVideo">Nuevo video</a>
            </div>
            <div class="box-body">
                <div class="row">
                    @foreach(collect([0,1,2,3,4,5])->toArray() as $index)
                        <div class="col-md-4" style="margin-top: 20px">
                            @if(isset($package->videos[$index]))
                                <div class="embed-responsive embed-responsive-4by3">
                                    {!! $package->videos[$index]->video_html !!}
                                </div>
                                {!! Form::model($package, ['route' => ['admin.packages.updateVideos', $package->id], 'method' => 'patch']) !!}
                                <h5>{!! $package->videos[$index]->name !!}
                                    <div class="btn-group btn-group-sm pull-right">
                                        <button type="button" data-toggle="modal" data-target="#uploadVideo"
                                                data-video-id="{!! $package->videos[$index]->id !!}"
                                                data-video-name="{!! $package->videos[$index]->name !!}"
                                                data-video-url="{!! $package->videos[$index]->url !!}"
                                                class="btn"><i class="fa fa-pencil"></i></button>
                                        <button onclick="return confirm('Realmente queres borrar el video {!! $package->videos[$index]->name !!}?')"
                                                type="submit" name="delete_video"
                                                value="{!! $package->videos[$index]->id !!}" class="btn"><i
                                                    class="fa fa-trash"></i></button>
                                    </div>
                                </h5>
                                {!! Form::close() !!}
                            @else
                                <div class="embed-responsive embed-responsive-4by3">
                                    <div class="well well-lg">
                                        <a href="#" role="button" data-toggle="modal" data-target="#uploadVideo">Click
                                            aqui para
                                            cargar un nuevo video</a>

                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                    <div class="btn-group col-sm-12" style="margin-top: 20px">
                        <a href="{!! route('admin.packages.edit', [$package->id]) !!}"
                           class="btn btn-default btn-gradient">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open
    modal for @mdo
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Open
    modal for @fat
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
        data-whatever="@getbootstrap">Open modal for @getbootstrap
</button>
...more buttons...
--}}

    <div class="modal fade" id="uploadVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::model($package, ['route' => ['admin.packages.updateVideos', $package->id], 'method' => 'patch']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Carga un nuevo video para {!! $package->name !!}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('name','Titulo:', ['class'=>"control-label"]) !!}
                        {!! Form::text('name', null, ["class"=>"form-control"]) !!}
                        <p class="text-muted">Ingresa un titulo descriptivo</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('url','URL:', ['class'=>"control-label"]) !!}
                        {!! Form::url('url', null, ["class"=>"form-control"]) !!}
                        <p class="text-muted">Pega solo la url del video, Ej:
                            https://www.youtube.com/watch?v=_Uu12zY01ts</p>
                        {!! Form::hidden('video', null, ["id"=>"video_id"]) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#uploadVideo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var id = button.data('video-id')
                var name = button.data('video-name')
                var url = button.data('video-url')
                var modal = $(this)

                if (id) {
                    modal.find('.modal-body input[name="name"]').val(name || '')
                    modal.find('.modal-body input[name="url"]').val(url || '')
                    modal.find('.modal-body input[name="video"]').val(id)
                } else {
                    modal.find('.modal-body input[name="name"]').val()
                    modal.find('.modal-body input[name="url"]').val()
                    modal.find('.modal-body input[name="video"]').val()
                }

            })
        })
    </script>
@endsection
