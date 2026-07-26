<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'BOSHUN')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body>
<!-- Nhúng header -->
@include('UserPage.layouts.header')

<main>
    @yield('content')
</main>

<!-- Footer (nếu có) -->
@include('UserPage.layouts.footer')


{{--<script src="{{ asset('js/app.js') }}"></script>--}}
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v24.0">
</script>
@stack('scripts')
@vite('resources/js/app.js')
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-H3FTVS77SM"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-H3FTVS77SM');
</script>
</body>
</html>
