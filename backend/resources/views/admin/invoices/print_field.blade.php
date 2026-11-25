<table class="table table-bordered">
    <thead>
    <tr>
        <th>CANT</th>
        <th>{!! $product->name !!}</th>
        <th>OK</th>
    </tr>
    </thead>
    <tbody>
    @foreach($product->flavors->sortBy('name')->groupBy('sub_family_id') as $key => $subFamily)
        {{--@if($subFamily[0]->subFamily)
            <tr>
                <td colspan="100">
                    {!! $subFamily[0]->subFamily->name !!}
                </td>
            </tr>
        @endif--}}
        @foreach($subFamily as $index => $flavor)
            <?php
            $item = $invoice->items->filter(function ($item) use ($flavor) {
                return $item->model_id === $flavor->id;
            })->first();
            ?>
            <tr>
                <td>{!! $item ? $item->qty : 0 !!}</td>
                <td>{!! $flavor->name !!}
                    @if($flavor->description)
                        <p>
                            <small class="text-info"><i
                                        class="fa fa-info-circle"></i> {!! $flavor->description !!}</small>
                        </p>
                    @endif
                </td>
                <td></td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>