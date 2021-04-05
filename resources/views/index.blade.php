<!-- 
Name: Arindam Roy
Email: arindam.roy.developer@gmail.com
Mobile: 9836395513
-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="icon" href="{{ asset('public/favicon.png') }}" type="image/png" sizes="16x16">
        <title>CodePad++</title>
        <link rel="stylesheet" href="{{ asset('public/assets/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/assets/fontawesome/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
    </head>
    <body>
        <noscript>
            <strong>We're sorry JavaScript enabled. Please enable it to continue.</strong>
        </noscript>
        <div id="app">
            <page-loader :is-Page-Loading="isPageLoadingActive"></page-loader>
            <app-index></app-index>
        </div>
        <div class="footer">
            <div class="container">
                <p class="ftxt">{{config('app.devcontact')}}</p>
            </div>
        </div>
    </body>
    <script src="{{ asset('public/assets/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="{{ asset('public/js/custom.js') }}"></script>
</html>
