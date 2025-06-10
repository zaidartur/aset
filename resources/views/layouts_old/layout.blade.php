<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
        <title>@yield('title')</title>
        
        @include('layouts.css')
        @yield('css')
    </head>

    <body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
        <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>

        <!-- sidenav  -->
        @include('layouts.sidebar')
        <!-- end sidenav -->

        <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">

        <!-- Navbar -->
        @include('layouts.header')
        <!-- end Navbar -->

        <!-- cards -->
        <div class="w-full px-6 py-6 mx-auto">
            @yield('content')

            @include('layouts.footer')
        </div>
        <!-- end cards -->
        </main>
        
        @include('layouts.configurator')
    </body>
  
    @include('layouts.js')
    @yield('js')
</html>
