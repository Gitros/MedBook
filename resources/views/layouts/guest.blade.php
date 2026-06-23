<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MedBook — {{ $title ?? 'Logowanie' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen grid md:grid-cols-2">
            <!-- Lewa: branding -->
            <div class="hidden md:flex bg-gradient-to-br from-indigo-600 to-blue-700 text-white p-12 flex-col justify-between">
                <a href="/" class="flex items-center gap-2">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="text-2xl font-bold">MedBook</span>
                </a>

                <div>
                    <h2 class="text-4xl font-bold leading-tight mb-4">Zdrowie zaczyna się od dobrej rezerwacji.</h2>
                    <p class="text-indigo-100 text-lg">Umawiaj wizyty u specjalistów szybko, wygodnie, z każdego miejsca.</p>
                </div>

                <div class="flex gap-6 text-sm">
                    <div>
                        <div class="text-3xl font-bold">30+</div>
                        <div class="text-indigo-200">lekarzy</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">15</div>
                        <div class="text-indigo-200">specjalizacji</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">24/7</div>
                        <div class="text-indigo-200">dostępność</div>
                    </div>
                </div>
            </div>

            <!-- Prawa: formularz -->
            <div class="flex flex-col items-center justify-center p-6 sm:p-12 bg-gray-50">
                <div class="md:hidden mb-6 flex items-center gap-2 text-indigo-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="text-2xl font-bold text-gray-800">MedBook</span>
                </div>

                <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-sm text-gray-500">© {{ date('Y') }} MedBook</p>
            </div>
        </div>
    </body>
</html>
