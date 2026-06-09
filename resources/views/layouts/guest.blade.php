<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FunShirt - Loja Online</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 bg-gradient-to-br from-indigo-50 via-white to-fuchsia-50">
            <a href="{{ url('/') }}" class="flex items-center gap-2 mb-6">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-white shadow-md">
                    <x-application-logo class="w-7 h-7" />
                </span>
                <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-fuchsia-600 bg-clip-text text-transparent">FunShirt</span>
            </a>

            <div class="w-full sm:max-w-md px-6 py-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-500">© {{ date('Y') }} FunShirt</p>
        </div>
    </body>
</html>
