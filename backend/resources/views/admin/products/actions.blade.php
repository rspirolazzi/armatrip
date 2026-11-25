{!! Html::actionsNav([
    ['route'=>'admin.products.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar productos', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/products*'],
    ['route'=>'admin.products.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de producto', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/products/create*'],
]) !!}