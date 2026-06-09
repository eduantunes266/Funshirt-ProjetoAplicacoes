<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FunShirt - Loja Online</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-50 via-white to-fuchsia-50 text-gray-800 flex items-center justify-center px-4 py-12">
    <div class="max-w-3xl w-full text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-white shadow-lg mb-6">
            <x-application-logo class="w-12 h-12" />
        </div>

        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">
            <span class="bg-gradient-to-r from-indigo-600 to-fuchsia-600 bg-clip-text text-transparent">FunShirt</span>
        </h1>
        <p class="mt-3 text-lg text-gray-600">T-shirts personalizadas. Escolhe, personaliza e veste.</p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold shadow-sm hover:bg-indigo-700 transition">
                Ver Catálogo
            </a>
            @guest
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-6 py-3 text-gray-800 font-semibold shadow-sm hover:bg-gray-50 transition">
                    Entrar
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-fuchsia-600 px-6 py-3 text-white font-semibold shadow-sm hover:bg-fuchsia-700 transition">
                    Criar conta
                </a>
            @endguest
        </div>

        <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
            <div class="bg-white rounded-2xl ring-1 ring-gray-200 shadow-sm p-5">
                <div class="text-indigo-600 font-bold text-2xl">01</div>
                <h3 class="mt-2 font-semibold text-gray-900">Escolhe</h3>
                <p class="text-sm text-gray-500 mt-1">Cor, tamanho e estampa no nosso catálogo.</p>
            </div>
            <div class="bg-white rounded-2xl ring-1 ring-gray-200 shadow-sm p-5">
                <div class="text-fuchsia-600 font-bold text-2xl">02</div>
                <h3 class="mt-2 font-semibold text-gray-900">Personaliza</h3>
                <p class="text-sm text-gray-500 mt-1">Carrega a tua imagem na área de cliente.</p>
            </div>
            <div class="bg-white rounded-2xl ring-1 ring-gray-200 shadow-sm p-5">
                <div class="text-emerald-600 font-bold text-2xl">03</div>
                <h3 class="mt-2 font-semibold text-gray-900">Recebe</h3>
                <p class="text-sm text-gray-500 mt-1">Pagas online e recebemos a tua encomenda.</p>
            </div>
        </div>

        <p class="mt-10 text-xs text-gray-500">© {{ date('Y') }} FunShirt</p>
    </div>
</body>
</html>
