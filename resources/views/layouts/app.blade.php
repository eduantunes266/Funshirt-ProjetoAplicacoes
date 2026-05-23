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
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-5 text-sm font-semibold text-gray-700">
                        <a href="{{ route('home') }}" class="hover:text-indigo-600">Catálogo</a>
                        <a href="{{ route('cart.index') }}" class="hover:text-indigo-600">Carrinho</a>
                        <a href="{{ route('custom.create') }}" class="hover:text-indigo-600">Personalizar</a>

                        @auth
                            @if (auth()->user()->isEmployee() || auth()->user()->isAdmin())
                                <a href="{{ route('orders.index') }}" class="hover:text-indigo-600">Encomendas</a>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Painel</a>
                                <a href="{{ route('admin.staff.index') }}" class="hover:text-indigo-600">Administração</a>
                            @endif
                        @endauth
                    </div>

                    <div class="flex items-center gap-4 text-sm font-semibold text-gray-700">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="hover:text-indigo-600">
                                @if (auth()->user()->isEmployee())
                                    A minha conta
                                @else
                                    Perfil
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-500">Sair</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-indigo-600">Login</a>
                            <a href="{{ route('register') }}" class="hover:text-indigo-600">Registo</a>
                        @endauth
                    </div>
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