<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('mazer') }}/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('mazer') }}/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('mazer') }}/css/app.css">
    <link rel="stylesheet" href="{{ asset('mazer') }}/css/pages/auth.css">
</head>

<body>
    <div id="auth">
        @yield('content')
    </div>
    <script src="{{ asset('mazer') }}/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('mazer') }}/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('mazer') }}/js/main.js"></script>
</body>

</html>
