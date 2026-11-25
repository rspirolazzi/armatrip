{!! Html::actionsNav([
    ['route'=>'admin.administrators.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar usuarios', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/administrators*'],
    ['route'=>'admin.administrators.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de usuario', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/administrators/create*'],
]) !!}