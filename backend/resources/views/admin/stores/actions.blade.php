{!! Html::actionsNav([
    ['route'=>'admin.stores.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar empresas', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/stores*'],
    ['route'=>'admin.stores.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de empresa', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/stores/create*'],
]) !!}