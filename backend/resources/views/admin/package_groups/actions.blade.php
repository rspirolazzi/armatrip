{!! Html::actionsNav([
    ['route'=>'admin.packageGroups.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar grupos', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/package-groups*'],
    ['route'=>'admin.packageGroups.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de grupo', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/package-groups/create*'],
]) !!}