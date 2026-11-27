<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Armatrip Admin</title>
    <link rel="icon" href="/favicon.png?v=armatrip" type="image/png"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;700&amp;family=Outfit:wght@500;700;800&amp;display=swap">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    
    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{!! config('app.url_fix_images') !!}/css/admin.css?t=20261125d">
    <link rel="stylesheet" href="{!! config('app.url_fix_images') !!}/css/comanda.css?t=20261125d">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{!! config('app.url_fix_images') !!}/vendor/tagsinput/tagsinput.css">
    <link rel="stylesheet" href="{!! config('app.url_fix_images') !!}/vendor/dropzone-5.7.0/dist/min/dropzone.min.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    @yield('css')
</head>

<body class="armatrip skin-blue sidebar-mini">
    <div class="wrapper" id="app">

        <!-- Main Header -->
        <header class="main-header">
            <!-- Logo -->
            <a href="{!! config('app.url_fix_images') !!}/admin" class="logo">
                <img src="{!! config('app.url_fix_images') !!}/img/logo-negativo.svg" alt="Armatrip"/>
            </a>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    @auth()
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <i class="fa fa-user-circle"></i>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <i class="fa fa-user-circle"></i>
                                    <p>
                                       {!! Auth::user()->name !!}
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{!! route('admin.administrators.show', Auth::user()->id) !!}" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Salir
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown info user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <i class="fa fa-info-circle"></i>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">Info</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <p>
                                       Nombre: {!! config('app.name') !!}
                                    </p>
                                    <p>
                                       Versión: {!! config('app.version') !!}
                                    </p>
                                    <p>
                                       Versión: {!! config('app.version') !!}
                                    </p>
                                </li>
                            </ul>
                        </li>
                        <!--<li class="dropdown notificaciones-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="badge" style="margin-right: 4px">0</span><i class="fa fa-bell"></i>
                                <span class="hidden-xs">Notificaciones</span>
                            </a>
                            <ul class="dropdown-menu">
                                
                            </ul>
                        </li>-->
                    </ul>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    <script src="{!! config('app.url_fix_images') !!}/js/app.js"></script>

    <!-- jQuery 3.1.1 -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/js/adminlte.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/js/autocomplete_code.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/vendor/ckeditor/ckeditor.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/vendor/tagsinput/tagsinput.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/vendor/dropzone-5.7.0/dist/min/dropzone.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $('[data-toggle="select2"]').select2()
            $('[data-toggle="datepicker"]').datepicker()
        })
    </script>
    @yield('scripts')
</body>
</html>