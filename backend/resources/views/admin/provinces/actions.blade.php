{!! Html::actionsNav([
    ['route'=>'admin.provinces.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar provincias', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/provinces*'],
    ['route'=>'admin.provinces.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de provincia', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/provinces/create*'],
]) !!}