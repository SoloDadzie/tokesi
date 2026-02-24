<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel Site')</title>
    <meta name="description" content="@yield('meta_description', 'Default meta description for your site.')">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="canonical" href="{{ url()->current() }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="theme-color" content="#d67a00">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- Include the header --}}
    @include('partials.header')

    {{-- Page content will go here --}}
    <main>
        @yield('content')
    </main>

    {{-- Include footer if needed --}}
    @include('partials.footer')
</body>
</html>
