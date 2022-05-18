<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('mazer') }}/css/main/app.css">
    @stack('css')
    <link rel="shortcut icon" href="{{ asset('mazer') }}/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('mazer') }}/images/logo/favicon.png" type="image/png">
</head>
</head>

<body>
    <div id="auth">
        @yield('content')
    </div>

    <script src="{{ asset('mazer') }}/js/app.js"></script>
</body>

</html>
