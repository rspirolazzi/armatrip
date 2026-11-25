<div class="row">
    @foreach($medias as $media)
        <div class="col-sm-4 col-md-3">
            <div class="thumbnail">
                <img src="{!! $media->getUrl() !!}" alt="{!! $media->name !!}" style="height: 200px">
                <div class="caption">
                    <h3>{!! $media->name !!}</h3>
                    <p><i class="fa fa-calendar"></i> {!! $media->created_at->format('d-m-Y H:i') !!}</p>
                    @if($media->mediable)
                        @switch($media->mediable->mediable_type)
                            @case (\App\Models\Admin\Package::class)
                            <p><a href="{!! route('admin.packages.show', $media->mediable->mediable_id) !!}"><i
                                            class="fa fa-briefcase"></i>
                                    @if($media->mediable->model)
                                        Paquete: {!! $media->mediable->model->name !!}
                                    @endif
                                </a>
                            </p>
                            @break
                            @case (\App\Models\Admin\PackageGroup::class)
                            <p><a href="{!! route('admin.packageGroups.show', $media->mediable->mediable_id) !!}"><i
                                            class="fa fa-object-group"></i>
                                    @if($media->mediable->model)
                                        Grupo: {!! $media->mediable->model->name !!}
                                    @endif
                                </a>
                            </p>
                            @break
                            @case (\App\Models\Admin\Brand::class)
                            <p><a href="{!! route('admin.brands.show', $media->mediable->mediable_id) !!}"><i
                                            class="fa fa-vcard"></i>
                                    @if($media->mediable->model)
                                        Marca: {!! $media->mediable->model->name !!}
                                    @endif
                                </a>
                            </p>
                            @break
                        @endswitch
                        <p><i class="fa fa-picture-o"></i> {!! $media->mediable->group !!}</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>