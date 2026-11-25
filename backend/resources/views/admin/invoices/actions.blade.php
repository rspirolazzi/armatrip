{!! Html::actionsNav([
    ['route'=>'admin.invoices.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar ordenes', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/invoices*'],
    ['route'=>'admin.invoices.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de orden', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/invoices/create*'],
]) !!}