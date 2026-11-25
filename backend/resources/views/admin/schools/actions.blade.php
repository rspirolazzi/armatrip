{!! Html::actionsNav([
    ['route'=>'admin.schools.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar escuelas', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/schools*'],
    ['route'=>'admin.schools.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de escuela', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/schools/create*'],
]) !!}