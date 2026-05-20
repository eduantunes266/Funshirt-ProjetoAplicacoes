<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FunShirt - Loja Online</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
            
            <nav class="bg-white shadow mb-4 p-4 flex justify-between items-center max-w-7xl mx-auto rounded-b-lg">
                <div class="flex gap-6 font-semibold text-gray-700">
                    <a href="{{ url('/') }}" class="hover:text-blue-600">Catálogo</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('cart.index') }}" class="hover:text-blue-600">Carrinho</a>
                    <a href="{{ route('custom.create') }}">Personalizar</a>
                </div>
                
                <div class="flex gap-4 font-semibold text-gray-700">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hover:text-blue-600 text-gray-950">Painel ({{ Auth::user()->name }})</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-red-600 text-red-500">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-blue-600">Registo</a>
                    @endauth
                </div>
            </nav>

            @isset($header)
                <header class="bg-white shadow max-w-7xl mx-auto mb-4 rounded-lg">
                    <div class="py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </body>
</html>