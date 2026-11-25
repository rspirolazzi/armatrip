{!! Html::actionsNav([
    ['route'=>'admin.brands.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar marcas', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/brands*'],
    ['route'=>'admin.brands.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de marca', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/brands/create*'],
]) !!}