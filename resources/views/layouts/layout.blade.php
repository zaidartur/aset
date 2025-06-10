<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>
        @yield('title')
    </title>

    @include('layouts.css')
    @yield('css')
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
    
        <!-- Navbar -->
        @include('layouts.header')
        <!-- End Navbar -->
        
        <div class="container-fluid py-4">
            @yield('content')
        
            @include('layouts.footer')
        </div>

    </main>
  
    @include('layouts.configurator')
    
    <form action="{{ route('logout') }}" method="POST" id="logout">
        @csrf
    </form>
    
    @include('layouts.js')
    @yield('js')  
  
</body>

</html>