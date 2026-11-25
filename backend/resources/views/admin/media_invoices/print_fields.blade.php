<div class="col-xs-8 col-md-8">
    @include('admin.media_invoices.print_field_chunk', ['product'=>$products[0]])
</div>
<div class="col-xs-4 col-md-4">
    @foreach($products->slice(1) as $index => $product)
        @include('admin.media_invoices.print_field', ['product'=>$product])
    @endforeach
</div>

