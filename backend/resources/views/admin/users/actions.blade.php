{!! Html::actionsNav([
    ['route'=>'admin.users.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar usuarios', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/users*'],
    ['route'=>'admin.users.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de usuario', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/users/create*'],
]) !!}