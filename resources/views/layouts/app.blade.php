<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/all.min.css') }}">
    @yield('pagestyle')

    <title>@yield('title')</title>
</head>
<body>

   @yield('content')

    {{-- <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script> --}}
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('js/fontawesome.min.js') }}"></script>
    @yield('pagejs')

</body>

</html>
