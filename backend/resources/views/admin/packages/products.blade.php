@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agregar productos a: {{$package->name}} - {{$package->store->name}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($package, ['route' => ['admin.packages.updateProducts', $package->id], 'method' => 'patch']) !!}
                    <div class="col-md-3">
                        <div class="box box-info">
                            <div class="box-header">
                                <h3 class="box-title">
                                    Categorias
                                </h3>
                            </div>
                            <div class="box-body">
                                <ul class="nav nav-pills nav-stacked nav-sm" id="myTabs">
                                    @foreach($products->groupBy('category_id') as $indexKey => $cat)
                                        @if($cat[0]->category)
                                            <li><a href="#tab_{!! $cat[0]->category['id'] !!}" data-target="#tab_{!! $cat[0]->category['id'] !!}" data-toggle="pill" alt="{!! $cat[0]->category['name'] !!} " title="{!! $cat[0]->category['name'] !!} ">{!! mb_strimwidth($cat[0]->category['name'],0,25,'...') !!} <span class="badge pull-right"></span></a></li>
                                        @else
                                            <li><a href="#tab_none" data-target="#tab_none" data-toggle="pill">Sin categoría <span class="badge pull-right"></span></a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            @foreach($products->groupBy('category_id') as $indexKey => $cat)
                                @if($cat[0]->category)
                                    <div class="tab-pane" id="tab_{!! $cat[0]->category['id'] !!}">
                                        @include('admin.packages.product-template', [
                                        'title'=>$cat[0]->category['name'],
                                        'description'=>$cat[0]->category['code'],
                                        'indexKey'=>$indexKey,
                                        'id'=>$cat[0]->category['id'],
                                        'cat'=>$cat,
                                        ])
                                    </div>
                                @else
                                    <div class="tab-pane" id="tab_none">
                                        @include('admin.packages.product-template', [
                                        'title'=>'Sin categoría',
                                        'description'=>'',
                                        'indexKey'=>$indexKey,
                                        'id'=>0,
                                        'cat'=>$cat,
                                        ])
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                    <div class="form-group col-sm-12 text-right">
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
        function calculateTotalOfProductsByCategory(category) {
            var total = $('[data-group="group_' + category + '"]').length
            var checked = $('[data-group="group_' + category + '"]:checked').length
            $('[data-target="#tab_'+category+'"] span').html(checked+'/'+total)
        }
        function calculateTotalOfProductsByCategories() {
            $('[data-toggle="check_group"]').each(function(i,e){
                var category = $(e).data('group').replace('group_', '')
                calculateTotalOfProductsByCategory(category)
            })
        }
    </script>
    <script type="text/javascript">
        $(function () {
            $('a[data-toggle="pill"]').on('hidden.bs.tab', function (e) {
                $($(e.target).data('target')).removeClass('show')
            })
            calculateTotalOfProductsByCategories();
        });
    </script>
    <script type="text/javascript">
        var checkAll = function (index, thiz) {
            var checked = thiz.checked
            $('input[data-group="group_' + index + '"]').prop('checked', checked);
            calculateTotalOfProductsByCategory(index)
        }
        var checkOne = function (index, thiz) {
            var checked = $('input[data-group="group_' + index + '"]').length === $('input[data-group="group_' + index + '"]:checked').length;
            $('#all_' + index).prop('checked', checked)
            calculateTotalOfProductsByCategory(index)
        }
    </script>
@endsection
