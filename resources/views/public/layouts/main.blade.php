<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="shortcut icon" href="{{ asset('kesit.ico') }}" type="image/x-icon">

    {{--  google fonts  --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@100;600;800&display=swap" rel="stylesheet">

    {{--  Fonts Icon  --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    {{--  stylesheet  --}}
    <link href="{{ asset('newser/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newser/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newser/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newser/css/style.css') }}" rel="stylesheet">
</head>

<body>
    @include('public.partials.header')

    {{-- main content --}}
    @yield('content')

    {{-- button back to top --}}
    <a href="#" class="btn btn-primary border-2 border-white rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    @include('public.partials.footer')

    {{-- js --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('newser/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('newser/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('newser/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('newser/js/main.js') }}"></script>
</body>

</html>
