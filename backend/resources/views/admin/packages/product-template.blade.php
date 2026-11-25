<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{!! $title !!} <small>({!! $description !!})</small></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <table class="table">
                <tr>
                    <th class="col-md-4">
                        <input type="checkbox" id="all_{!! $id !!}" name="all_{!! $id !!}"
                               onclick="checkAll('{!! $id !!}', this)"/>
                        <label for="all_{!! $id !!}">
                        Nombre
                        </label>
                    </th>
                    <th class="col-md-4">Precio</th>
                    <th class="col-md-2">Default</th>
                    <th class="col-md-2">Orden</th>
                </tr>
                @foreach($cat as $product)
                    <?php
                    $product_in_package = $package->products->firstWhere('id', $product->id);
                    ?>
                    <tr>
                        <td>
                            <label for="product_{!! $product->id !!}"><input
                                        data-group="group_{!! $id !!}"
                                        data-toggle="check_group"
                                        id="product_{!! $product->id !!}" type="checkbox"
                                        name="product[{!! $cat[0]->category['id'] !!}][products][{!! $product->id !!}][product_id]"
                                        {!! is_null($product_in_package) ? '' :'checked' !!}
                                        value="{!! $product->id !!}"
                                        onclick="checkOne('{!! $id !!}', this)"
                                /> {!! $product->name !!}</label>
                            @if($product->description)
                                <small class="help-block">{!! mb_strimwidth($product->description, 0, 100, '...') !!}</small>
                            @endif
                            <input
                                    type="hidden"
                                    name="product[{!! $cat[0]->category['id'] !!}][products][{!! $product->id !!}][id]"
                                    value="{!! !is_null($product_in_package) ? $product_in_package->pivot->id : null !!}"
                            />
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::number('product['.$cat[0]->category['id'].'][products]['.$product->id .'][price]', !is_null($product_in_package) && (!empty($product_in_package->pivot->price)) ? $product_in_package->pivot->price :$product->price, ['class' => 'form-control']) !!}
                            </div>
                        </td>
                        <td>
                            @if($cat[0]->category['multiple'])
                                {!! Form::checkbox('product['.$cat[0]->category['id'].'][default][]', $product->id, !is_null($product_in_package) ? $product_in_package->pivot->default :0) !!}
                            @else
                                {!! Form::radio('product['.$cat[0]->category['id'].'][default]', $product->id, !is_null($product_in_package) ? $product_in_package->pivot->default :0) !!}
                            @endif

                        </td>
                        <td>
                            {!! Form::number('product['.$cat[0]->category['id'].'][products]['.$product->id .'][order]',  !is_null($product_in_package) && (!empty($product_in_package->pivot->order)) ? $product_in_package->pivot->order :0, ['class' => 'form-control']) !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>