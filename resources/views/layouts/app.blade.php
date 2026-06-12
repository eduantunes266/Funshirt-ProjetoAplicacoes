<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FunShirt - Loja Online</title>

        {{-- Tipografia e Estilos --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-slate-50 to-indigo-50/40 min-h-screen text-gray-800">
        <div class="min-h-screen flex flex-col">
            
            {{-- Barra de Navegação Superior --}}
            <nav class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
                    
                    {{-- Lado Esquerdo: Logotipo e Links Principais --}}
                    <div class="flex items-center gap-6 text-sm font-medium text-gray-600">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-gray-900">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-white shadow-sm">
                                <x-application-logo class="w-5 h-5" />
                            </span>
                            <span class="text-base bg-gradient-to-r from-indigo-600 to-fuchsia-600 bg-clip-text text-transparent">FunShirt</span>
                        </a>

                        {{-- Menus de Navegação com base no tipo de utilizador --}}
                        <div class="hidden sm:flex flex-wrap items-center gap-5">
                            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Catálogo</a>
                            <a href="{{ route('cart.index') }}" class="hover:text-indigo-600 transition">Carrinho</a>

                            @auth
                                @if (auth()->user()->isCustomer())
                                    <a href="{{ route('custom-images.index') }}" class="hover:text-indigo-600 transition">T-Shirts Personalizadas</a>
                                    <a href="{{ route('my-orders.index') }}" class="hover:text-indigo-600 transition">As minhas encomendas</a>
                                @endif
                                @if (auth()->user()->isEmployee() || auth()->user()->isAdmin())
                                    <a href="{{ route('orders.index') }}" class="hover:text-indigo-600 transition">Encomendas</a>
                                @endif
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Painel</a>
                                    <a href="{{ route('admin.staff.index') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                                    <a href="{{ route('admin.tshirts.index') }}" class="hover:text-indigo-600 transition">Gestão Catálogo</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- Lado Direito: Perfil, Login e Registo --}}
                    <div class="flex items-center gap-3 text-sm font-medium">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-gray-700 hover:text-indigo-600 transition">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:inline">{{ auth()->user()->isEmployee() ? 'A minha conta' : 'Perfil' }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="rounded-lg border border-gray-200 px-3 py-1.5 text-gray-600 hover:bg-gray-50 hover:text-red-600 transition">Sair</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 transition">Login</a>
                            <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-white shadow-sm hover:bg-indigo-700 transition">Registo</a>
                        @endauth
                    </div>
                </div>
            </nav>

            {{-- Área do Título da Página (Opcional) --}}
            @isset($header)
                <header class="max-w-7xl mx-auto w-full mt-6 px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 py-5 px-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Área do Conteúdo Principal da Página --}}
            <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex-1">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            {{-- Rodapé da Aplicação --}}
            <footer class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-gray-500">
                © {{ date('Y') }} FunShirt
            </footer>
        </div>
    </body>
</html>
