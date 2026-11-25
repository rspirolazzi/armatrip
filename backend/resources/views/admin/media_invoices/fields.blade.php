@if(sizeof($category->children )==0)
    <div class="alert alert-info">No hay categorías cargadas</div>
@else
    <div class="row">
        @foreach($category->children as $product)
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title text-center">{!! $product->name !!}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">

                            @foreach($product->children as $_child)
                                <h3>
                                    <a class="list-group-item list-group-item-success text-center text-uppercase text-muted"
                                       href="{!! route('idg.media_invoices.category', $_child->id)  !!}">{!! $_child->name !!}</a>
                                </h3>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
