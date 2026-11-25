{!! Html::actionsNav([
    ['route'=>'admin.notifications.index', 'btnClass'=>'btn btn-lg btn-default btn-block btn-flat', 'title'=>'Administrar notificationes', 'icon'=>'fa fa-plus-circle fa-2x', 'request_is'=>'admin/notifications*'],
    ['route'=>'admin.notifications.create', 'btnClass'=>'btn btn-lg btn-default btn-default-light btn-block btn-flat', 'title'=>'Alta de notification', 'icon'=>'fa fa-gear fa-2x', 'request_is'=>'admin/notifications/create*'],
]) !!}