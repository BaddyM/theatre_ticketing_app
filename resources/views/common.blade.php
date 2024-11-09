<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.css') }}">
    <link rel="shortcut icon" href="favicon.png?v2" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
    @yield("body")
    <script src="{{ asset('assets/jquery.js') }}"></script>
    <script src="{{ asset('assets/jquery_qrcode.js') }}"></script>
    <script src="{{ asset('assets/bootstrap.bundle.js') }}"></script>
    @stack("scripts")
</body>
</html>