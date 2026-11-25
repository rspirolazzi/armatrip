@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agregar sabores a: {{$product->name}} - {{$product->category->name}}
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
                    {!! Form::model($product, ['route' => ['idg.products.updateItems', $product->id], 'method' => 'patch']) !!}
                    @foreach($items->groupBy('sub_category_id') as $indexKey => $cat)
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">
                                        <label for="all_{!! $indexKey !!}" >
                                        @if($cat[0]->subCategory)
                                            {!! $cat[0]->subCategory['name'] !!}
                                        @else
                                            @if($cat[0]->category)
                                                {!! $cat[0]->category['name'] !!}
                                            @else
                                                Sin categoría
                                            @endif
                                        @endif
                                        <input type="checkbox" id="all_{!! $indexKey !!}" name="all_{!! $indexKey !!}"
                                               onclick="checkAll('{!! $indexKey !!}', this)"/>
                                        </label>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        @foreach($cat as $item)
                                            <div class="col-md-4">

                                                <label for="item_{!! $item->id !!}"><input
                                                            data-group="group_{!! $indexKey !!}"
                                                            id="item_{!! $item->id !!}" type="checkbox"
                                                            name="item[]"
                                                            {!! is_null($product->items->firstWhere('id', $item->id)) ? '' :'checked' !!}
                                                            value="{!! $item->id !!}"
                                                            onclick="checkOne('{!! $indexKey !!}', this)"
                                                    /> {!! $item->name !!}</label>
                                                @if($item->description)
                                                    <small class="help-block">{!! $item->description !!}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                        <a href="{!! route('idg.products.edit', [$product->id]) !!}"
                           class="btn btn-default btn-gradient">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    var checkAll = function(index, thiz){
        var checked = thiz.checked
        $('input[data-group="group_'+index+'"]').prop('checked', checked);
    }
    var checkOne= function(index, thiz){
        var checked = $('input[data-group="group_'+index+'"]').length ===$('input[data-group="group_'+index+'"]:checked').length;
        $('#all_'+index).prop('checked', checked)
    }
</script>
@endsection
