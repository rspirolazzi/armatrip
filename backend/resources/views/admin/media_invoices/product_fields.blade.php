<div class="row">
    @if(empty($products)||sizeof($products)===0)
        No hay productos
    @else
        @foreach($products as $product)
            <div class="col-md-3">
                <div class="thumbnail">
                    <a name="product_id_{!! $product->id !!}"></a>
                    {!! Form::iconForMedia($product->image_primary, ['title'=>$product->name, 'id'=>$product->id]) !!}
                    <div class="caption">
                        <div style="height: 112px">
                            <h4 style="min-height: 40px">
                                <b>{!! \Illuminate\Support\Str::limit($product->name, 40) !!}</b></h4>
                            <div>
                                <b class="text-danger">{!! App\Helpers\Helper::formatPrice($product->price)!!}</b>
                            </div>
                            <small style="min-height: 40px; display: block">{!! \Illuminate\Support\Str::limit($product->description, 100) !!}</small>
                            @if($product->min>1)
                                <small class="text-warning">Cantidad
                                    mínima: {!! $product->min !!}</small>
                            @endif
                        </div>
                        <hr/>
                        <div class="">
                            <div class="row">
                                <div class="col-md-9 col-xs-12">
                                    {!! Form::open(['route' => 'idg.media_invoices.update_item', 'method' => 'post']) !!}
                                    {!! Form::hidden('redirect_to', route('idg.media_invoices.category', $category->id)) !!}
                                    @if($product['qtyFromCart']==0)
                                        <button type="submit"
                                                alt="Agregar {!! $product->name !!} al carrito"
                                                title="Agregar {!! $product->name !!} al carrito"
                                                class="btn btn-gradient btn-info btn-block "
                                                role="button">Comprar <i
                                                    class="fa fa-plus"></i></button>
                                        {!! Form::hidden('qty', $product->min) !!}

                                    @else
                                        <div class="input-group">
                                            <input name="qty" type="number" class="form-control "
                                                   placeholder="Cantidad"
                                                   value="{!! $product['qtyFromCart'] !!}"/>
                                            <span class="input-group-btn">
                                                        <button type="submit"
                                                                alt="Actualizar cantidad de {!! $product->name !!}"
                                                                title="Actualizar cantidad de {!! $product->name !!}"
                                                                class="btn btn-gradient btn-info " role="button"><i
                                                                    class="fa fa-refresh"></i></button>
                                                    </span>
                                        </div>
                                    @endif
                                    {!! Form::hidden('id', $product->id) !!}
                                    {!! Form::close() !!}
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    {!! Form::downloadLinkForMedia($product->image_secondary, ['title'=>$product->name]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@section('scripts')
    <script type="text/javascript">
        $(function () {
            var hash = window.location.hash.replace('#', '')
            jQuery('a[name=' + hash + ']').parent('.thumbnail').addClass('bg-info')
        });
    </script>
@endsection