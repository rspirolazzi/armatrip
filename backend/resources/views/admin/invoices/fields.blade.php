@foreach($products as $index => $product)
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h2 class="box-title text-center">{!! $product->name !!}
                    <small>{!! $product->description !!}</small>
                </h2>
            </div>
            <div class="box-body">
                <div class="row form-horizontal">
                    @foreach($product->flavors->sortBy('name')->groupBy('sub_family_id') as $key => $subFamily)
                        @if($subFamily[0]->subFamily)
                            <div class="col-md-12">
                                <h4 class="text-center">{!! $subFamily[0]->subFamily->name !!}</h4>
                                <hr/>
                            </div>
                        @endif
                        @foreach($subFamily as $index => $flavor)
                            <div class="col-md-4" id="{!! md5("{$product->id}_{$flavor->id}_$index") !!}">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <?php
                                        $item = null;
                                        if (isset($invoice)) {
                                            $item = $invoice->items->filter(function ($item) use ($flavor) {
                                                return $item->model_id === $flavor->id;
                                            })->first();

                                        }
                                        ?>
                                        {!! Form::number('items['.get_class($flavor) .']['.$flavor->id .']', $item ? $item->qty : 0, ['class' => 'form-control text-center', 'data-row'=>md5("{$product->id}_{$flavor->id}_$index")]) !!}
                                    </div>
                                    <label class="col-sm-9">{!! $flavor->name !!}
                                        @if($flavor->description)
                                            <p>
                                                <small class="text-info"><i
                                                            class="fa fa-info-circle"></i> {!! $flavor->description !!}
                                                </small>
                                            </p>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar Pedido', ['class' => 'btn btn-primary']) !!}
    @can('admin.invoices.index')<a href="{!! route('admin.invoices.index') !!}" class="btn btn-default">Volver</a>@endcan
</div>
@section('scripts')
    <script type="text/javascript">
        $(function () {
            var fnOnChange = function (element) {
                var id = '#' + $(element).data('row')
                if ($(element).val() == 0) {
                    $(id).removeClass('bg-danger');
                } else {
                    $(id).addClass('bg-danger');
                }
            }
            $('input[type=number]').on('change', function (e) {
                fnOnChange(this)
            });
            $('input[type=number]').each(function (i, e) {
                fnOnChange(e)
            });
        })
    </script>
@endsection