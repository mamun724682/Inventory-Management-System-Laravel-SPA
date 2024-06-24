<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <link rel="icon" type="image/x-icon" href="{{ asset("assets/img/favicon.png") }}">
    <meta name="description" content=""/>

    <title inertia>{{ config('app.name', 'Inventory Management System') }}</title>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="text-blueGray-700 antialiased">
@inertia
</body>
</html>
