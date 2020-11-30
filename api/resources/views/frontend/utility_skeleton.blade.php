<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#00c281"> {{-- #1d2530 --}}

    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">

    <style>
        /*** Resets ***/
        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
            display: block;
        }

        /*** Basic styling ***/
        body {
            background-color: #e9ecef;
            color: #4c6876;
            font-family: "Raleway", "Work Sans", "Roboto", "Helvetica Neue", Arial, BlinkMacSystemFont, -apple-system, "Segoe UI", "Noto Sans", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: .9rem;
            font-weight: 400;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            text-align: left;
        }

        form {
            display: block;
            margin: 0 auto;
            width: 75%;
        }

        header, footer {
            color: #ffffff;
            font-size: .9rem;
            padding: .75rem;
            text-align: center;
        }

        main {
            background-color: #ffffff;
            box-shadow: 1px 2px 5px rgba(1,1,1,.16);
            padding: .75rem;
            -webkit-box-shadow: 1px 2px 5px rgba(1,1,1,.16);
        }

        a {
            color: #00c281;
            text-decoration: none;
            transition: color 150ms ease-in-out;
        }

        a:hover {
            color: #00764e;
            text-decoration: none;
        }

        button {
            background-color: transparent;
            border: 1px solid transparent;
            border-radius: .25rem;
            color: #4c6876;
            cursor: pointer;
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.5;
            padding: 0.5rem 1.5rem;
            text-align: center;
            -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            vertical-align: middle;
        }

        button.block {
            display: block;
            width: 100%;
        }

        button.primary {
            color: #fff;
            background-color: #00c281;
            border-color: #00c281;
        }

        button.secondary {
            color: #fff;
            background-color: #1d2530;
            border-color: #1d2530;
        }

        button.small {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: bold;
            margin: 0;
        }

        h1 {
            font-size: 1.75rem;
        }

        h2 {
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
        }

        h3, h4, h5, h6 {
            font-size: .9rem
        }

        hr {
            border: 0;
            border-top: 1px solid #ced4da;
        }

        input, select {
            background-clip: padding-box;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            color: #4c6876;
            display: block;
            font-size: 1rem;
            font-weight: 400;
            padding: .375rem .75rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            width: 100%;
        }

        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"] + label {
            position: relative;
            display: inline-flex;
            cursor: pointer;
        }

        input[type="checkbox"] + label:before {
            width: 2.25rem;
            height: 1.25rem;
            border-radius: 1.25rem;
            border: 2px solid #ddd;
            background-color: #EEE;
            content: "";
            margin-right: 1rem;
            transition: background-color 250ms linear;
        }

        input[type="checkbox"] + label:after {
            width: 1rem;
            height: 1rem;
            border-radius: 1rem;
            background-color: #fff;
            content: "";
            transition: margin 0.1s linear;
            box-shadow: 0 0 5px #aaa;
            position: absolute;
            left: 2px;
            top: 2px;
        }

        input[type="checkbox"]:checked + label:before {
            background-color: #00c281;
        }

        input[type="checkbox"]:checked + label:after {
            margin: 0 0 0 1rem;
        }

        input:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
            opacity: 1;
        }

        input:focus, select:focus {
            background-color: #fff;
            border-color: #00c281;
            box-shadow: 0 0 0 0.2rem rgba(0, 194, 129, .25);
            color: #495057;
            outline: 0;
        }

        label {
            display: block;
        }

        p {
            margin: 0 0 .5rem 0
        }

        ul {
            margin: .5rem 0;
        }

        #container {
            background-color: #1d2530;
            border-radius: 5px;
            margin: 0 auto;
            width: 1140px;
        }

        #imprint {
            font-size: .75rem;
            margin: .25rem auto 0;
            text-align: center;
            width: 1140px;
        }

        .alert {
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
            position: relative;
            padding: .75rem 1.25rem;
        }

        .alert.danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert.success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .d-block { display: block !important; }
        .d-none { display: none !important; }

        .flex {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .flex.align-items-center {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .flex.flex-column {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .flex.justify-content-between {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .flex.flex-wrap {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-row {
            margin: 0 -0.5rem;
        }

        .form-row .form-group {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            -webkit-box-flex: 1;
            flex-grow: 1;
            max-width: 100%;
            padding: 0 .5rem;
        }

        .has-error {
            background-color: rgba(241, 70, 104, 0.1);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-position: right calc(.375em + .1875rem) center;
            background-repeat: no-repeat;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
            border-color: #f14668;
            padding-right: calc(1.5em + .75rem);
        }

        .has-error:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
        }

        .invalid-feedback {
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
            color: #dc3545;
        }

        .mb-1 { margin-bottom: .25rem; }
        .mb-2 { margin-bottom: .5rem; }
        .mb-3 { margin-bottom: .75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-7 { margin-bottom: 1.75rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mr-1 { margin-right: .25rem; }
        .mr-2 { margin-right: .5rem; }
        .mr-3 { margin-right: .75rem; }
        .mr-4 { margin-right: 1rem; }
        .mr-5 { margin-right: 1.25rem; }
        .mr-6 { margin-right: 1.5rem; }
        .mr-7 { margin-right: 1.75rem; }
        .mr-8 { margin-right: 2rem; }
        .my-1 { margin-bottom: .25rem; margin-top: .25rem; }
        .my-2 { margin-bottom: .5rem; margin-top: .5rem; }
        .my-3 { margin-bottom: .75rem; margin-top: .75rem; }
        .my-4 { margin-bottom: 1rem; margin-top: 1rem; }
        .my-5 { margin-bottom: 1.25rem; margin-top: 1.25rem; }
        .my-6 { margin-bottom: 1.5rem; margin-top: 1.5rem; }
        .my-7 { margin-bottom: 1.75rem; margin-top: 1.75rem; }
        .my-8 { margin-bottom: 2rem; margin-top: 2rem; }

        .nowrap {
            white-space: nowrap;
        }

        .w50 {
            width: 50%;
        }

        /** Media queries **/
        @media (max-width: 1160px) {
            h1 {
                font-size: 1.5rem;
            }

            #container, #imprint {
                width: 100%;
            }
        }
        @media (max-width: 780px) {
            form {
                width: 100%;
            }
        }
        @media (max-width: 432px) {
            body {
                padding: 0 0 20px 0;
            }

            h1 {
                font-size: 1.25rem;
            }

            h2 {
                font-size: 1rem;
            }

            #container {
                border-radius: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div id="container">
    <header>
        <h1>@yield('header')</h1>
    </header>

    <main>
        <!-- content start -->
    @yield('content')
    <!-- content end-->

    </main>

    <footer>
        <strong>
            Blue skies, {{ appName() }} team<br>
            <a href="mailto:{{ appSupportMail() }}">{{ appSupportMail() }}</a>
        </strong>
    </footer>
</div>

<div id="imprint">
    Impressum | Datenschutz{{-- TODO: Link to legals --}}
</div>

@stack('scripts')

</body>
</html>
