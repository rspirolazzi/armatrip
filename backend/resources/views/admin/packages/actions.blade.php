{!! Html::actionsNav([
    ['route'=>'admin.packages.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar paquetes', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/packages*'],
    ['route'=>'admin.packages.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de paquete', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/packages/create*'],
]) !!}