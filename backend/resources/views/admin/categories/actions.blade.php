{!! Html::actionsNav([
    ['route'=>'admin.categories.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar categorias', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/categories*'],
    ['route'=>'admin.categories.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de categoria', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/categories/create*'],
]) !!}