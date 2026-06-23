<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedBook — System rejestracji wizyt</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-blue-50 min-h-screen">

    <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="h-9 w-9 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span class="font-bold text-2xl text-gray-800">MedBook</span>
        </div>
        <div class="flex gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Pulpit</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">Logowanie</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Rejestracja</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pt-16 pb-24">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-5xl font-bold text-gray-900 leading-tight">
                Twoja przychodnia. <span class="text-indigo-600">Online.</span>
            </h1>
            <p class="mt-6 text-xl text-gray-600">
                Rezerwuj wizyty u lekarzy specjalistów w kilka kliknięć.
                Zarządzaj swoim kalendarzem, historią wizyt i danymi w jednym miejscu.
            </p>
            <div class="mt-10 flex gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg text-lg hover:bg-indigo-700 shadow-lg">Załóż konto</a>
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-indigo-600 border border-indigo-600 rounded-lg text-lg hover:bg-indigo-50">Mam już konto</a>
                @endguest
            </div>
        </div>

        <div class="mt-24 grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Rezerwacja online</h3>
                <p class="text-gray-600">Wybierz lekarza, termin i zarezerwuj wizytę bez kolejek i telefonów.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Specjaliści</h3>
                <p class="text-gray-600">Lekarze różnych specjalizacji z transparentnymi cennikami.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Bezpieczne dane</h3>
                <p class="text-gray-600">Hasła szyfrowane bcryptem, role i autoryzacja na każdym endpoincie.</p>
            </div>
        </div>
    </main>

    <footer class="text-center py-6 text-sm text-gray-500">
        MedBook © {{ date('Y') }} — Projekt zaliczeniowy PZSI
    </footer>
</body>
</html>
