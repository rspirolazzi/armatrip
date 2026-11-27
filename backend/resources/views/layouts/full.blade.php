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

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{!! config('app.url_fix_images') !!}/css/admin.css">
    <link rel="stylesheet" href="{!! config('app.url_fix_images') !!}/css/comanda.css">

    @yield('css')
</head>

<body class="armatrip skin-blue sidebar-mini">
    <div class="wrapper" id="app">

        <!-- Main Header -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        @include('layouts.partials.version')
    </div>


    <!-- jQuery 3.1.1 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/js/adminlte.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/js/app.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/js/autocomplete_code.js"></script>
    <script src="{!! config('app.url_fix_images') !!}/vendor/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">

    </script>
    @yield('scripts')
</body>
</html>