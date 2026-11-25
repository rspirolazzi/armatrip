<div class="row">
    @foreach($product->flavors->chunk(ceil($product->flavors->count()/2)) as $flavors)
        <div class="col-xs-6 col-md-6">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>CANT</th>
                    <th>{!! $product->name !!}</th>
                    <th>OK</th>
                </tr>
                </thead>
                <tbody>
                @foreach($flavors->sortBy('name') as $key => $flavor)
                    <?php
                    $item = $media_invoice->items->filter(function ($item) use ($flavor) {
                        return $item->model_id === $flavor->id;
                    })->first();
                    ?>
                    <tr>
                        <td>{!! $item ? $item->qty : 0 !!}</td>
                        <td>{!! $flavor->name !!}
                            @if($flavor->description)
                                <p>
                                    <small class="text-info"><i
                                                class="fa fa-info-circle"></i> {!! $flavor->description !!}
                                    </small>
                                </p>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>