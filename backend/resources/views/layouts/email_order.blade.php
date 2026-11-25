<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- If you delete this meta tag, Half Life 3 will never be released. -->
    <meta name="viewport" content="width=device-width"/>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Armatrip - Orden</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;700&amp;family=Outfit:wght@500;700;800&amp;display=swap" rel="stylesheet">

    <style>
        /* -------------------------------------
            GLOBAL
    ------------------------------------- */
        * {
            margin: 0;
            padding: 0;
        }

        * {
            font-family: 'Figtree', Arial, sans-serif;
            font-size: 22px;
        }

        img {
            max-width: 100%;
        }

        .collapse {
            margin: 0;
            padding: 0;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            font-size: 20px;
        }

        /* -------------------------------------
                ELEMENTS
        ------------------------------------- */
        a {
            color: #0A5560;
        }

        .btn {
            text-decoration: none;
            color: #FFF;
            background-color: #666;
            padding: 10px 16px;
            font-weight: bold;
            margin-right: 10px;
            text-align: center;
            cursor: pointer;
            display: inline-block;
        }

        p.callout {
            padding: 15px;
            background-color: #F3F8F7;
            margin-bottom: 15px;
        }

        .callout a {
            font-weight: bold;
            color: #0A5560;
        }

        table.order {
            /* 	padding:15px; */
            background-color: #ebebeb;

        }

        .installment{
            font-size: 36px;
            font-weight: bolder;
        }
        .price{
            font-size: 26px;
            font-weight: bolder;
        }

        ul.list-categories{list-style: none}
        ul.list-categories li.list-category{
            font-weight: bolder;
        }
        ul.list-products{list-style-type: disc}
        ul.list-products li.list-product{}



        /* -------------------------------------
                HEADER
        ------------------------------------- */
        table.head-wrap {
            width: 100%;
        }

        .header.container table td.logo {
            padding: 15px;
        }

        .header.container table td.label {
            padding: 15px;
            padding-left: 0px;
        }

        /* -------------------------------------
                BODY
        ------------------------------------- */
        table.body-wrap {
            width: 100%;
        }

        /* -------------------------------------
                FOOTER
        ------------------------------------- */
        table.footer-wrap {
            width: 100%;
            clear: both !important;
        }

        .footer-wrap .container td.content p {
            border-top: 1px solid rgb(215, 215, 215);
            padding-top: 15px;
        }

        .footer-wrap .container td.content p {
            font-size: 10px;
            font-weight: bold;

        }

        /* -------------------------------------
                TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Figtree', Arial, sans-serif;
            line-height: 1.1;
            margin-bottom: 15px;
            color: #000;
        }

        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small {
            font-size: 60%;
            color: #6f6f6f;
            line-height: 0;
            text-transform: none;
        }

        h1 {
            font-weight: 200;
            font-size: 44px;
        }

        h2 {
            font-weight: 200;
            font-size: 37px;
        }

        h3 {
            font-weight: 500;
            font-size: 27px;
        }

        h4 {
            font-weight: 500;
            font-size: 23px;
        }

        h5 {
            font-weight: 900;
            font-size: 17px;
        }

        h6 {
            font-weight: 900;
            font-size: 14px;
            text-transform: uppercase;
            color: #444;
        }

        .collapse {
            margin: 0 !important;
        }

        p, ul {
            margin-bottom: 10px;
            font-weight: normal;
            line-height: 1.6;
        }

        .hello{
            color: #0A5560;
        }
        /* ---------------------------------------------------
                RESPONSIVENESS
                Nuke it from orbit. It's the only way to be sure.
        ------------------------------------------------------ */

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important; /* makes it centered */
            clear: both !important;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            padding: 15px;
            max-width: 600px;
            margin: 0 auto;
            display: block;
        }

        /* Let's make sure tables in the content area are 100% wide */
        .content table {
            width: 100%;
        }

        /* Align items to the center */
        .text-center {
            text-align: center;
        }

        /* Odds and ends */
        .column {
            width: 300px;
            float: left;
        }

        .column tr td {
            padding: 15px;
        }

        .column-wrap {
            padding: 0 !important;
            margin: 0 auto;
            max-width: 600px !important;
        }

        .column table {
            width: 100%;
        }

        .social .column {
            width: 280px;
            min-width: 279px;
            float: left;
        }

        /* Be sure to place a .clear element after each set of columns, just to be safe */
        .clear {
            display: block;
            clear: both;
        }

        /* -------------------------------------------
                PHONE
                For clients that support media queries.
                Nothing fancy.
        -------------------------------------------- */
        @media only screen and (max-width: 600px) {

            a[class="btn"] {
                display: block !important;
                margin-bottom: 10px !important;
                background-image: none !important;
                margin-right: 0 !important;
            }

            div[class="column"] {
                width: auto !important;
                float: none !important;
            }

            table.social div[class="column"] {
                width: auto !important;
            }

        }
    </style>

</head>

<body bgcolor="#FFFFFF">

<!-- HEADER -->
<table class="head-wrap">
    <tr>
        <td class="header container">
            <div class="content">
                <table>
                    <tr>
                        <td><img width="150px" src="{{env('APP_URL_FIX_IMAGES')}}/email/images/logo-big.png"/></td>
                    </tr>
                </table>
            </div>

        </td>
    </tr>
</table><!-- /HEADER -->


<!-- BODY -->
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            @yield('content')

        </td>
        <td></td>
    </tr>
</table><!-- /BODY -->

<!-- FOOTER -->
{{--
<table class="footer-wrap">
    <tr>
        <td></td>
        <td class="container">

            <!-- content -->
            <div class="content">
                <table>
                    <tr>
                        <td align="center">
                            <p>
                                <a href="#">Terminos</a> |
                                <a href="#">Privacidad</a> |
                                <a href="#">
                                    <unsubscribe>Unsubscribe</unsubscribe>
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </div><!-- /content -->

        </td>
        <td></td>
    </tr>
</table><!-- /FOOTER -->
--}}

</body>
</html>
