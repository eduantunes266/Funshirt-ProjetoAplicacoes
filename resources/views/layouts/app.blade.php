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
          <nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div style="display: flex; gap: 20px; font-weight: bold; color: #333;">
        <a href="/" style="text-decoration: none; color: inherit;">Catálogo</a>
        <a href="/carrinho" style="text-decoration: none; color: inherit;">Carrinho</a>
        <a href="/personalizar" style="text-decoration: none; color: inherit;">Personalizar</a>
    </div>
    
    <div style="display: flex; gap: 15px; font-weight: bold; color: #333;">
        @auth
            <a href="/dashboard" style="text-decoration: none; color: inherit;">Painel</a>
            <form method="POST" action="/logout" style="margin: 0; display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #dc3545; font-weight: bold; cursor: pointer; padding: 0; font-size: 16px;">Sair</button>
            </form>
        @else
            <a href="/login" style="text-decoration: none; color: inherit;">Login</a>
            <a href="/register" style="text-decoration: none; color: inherit;">Registo</a>
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