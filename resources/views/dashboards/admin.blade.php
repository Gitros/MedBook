<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Panel administratora</h2></x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <a href="{{ route('doctors.index') }}" class="bg-white p-5 rounded-lg shadow hover:shadow-md transition flex items-center gap-3">
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['doctors'] }}</div>
                    <div class="text-sm text-gray-600">Lekarzy</div>
                </div>
            </a>
            <a href="{{ route('patients.index') }}" class="bg-white p-5 rounded-lg shadow hover:shadow-md transition flex items-center gap-3">
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['patients'] }}</div>
                    <div class="text-sm text-gray-600">Pacjentów</div>
                </div>
            </a>
            <a href="{{ route('specializations.index') }}" class="bg-white p-5 rounded-lg shadow hover:shadow-md transition flex items-center gap-3">
                <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['specializations'] }}</div>
                    <div class="text-sm text-gray-600">Specjalizacji</div>
                </div>
            </a>
            <a href="{{ route('appointments.index', ['from' => today()->format('Y-m-d'), 'to' => today()->format('Y-m-d')]) }}" class="bg-white p-5 rounded-lg shadow hover:shadow-md transition flex items-center gap-3">
                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['appointments_today'] }}</div>
                    <div class="text-sm text-gray-600">Wizyt dziś</div>
                </div>
            </a>
            <a href="{{ route('appointments.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-lg shadow hover:shadow-md transition flex items-center gap-3">
                <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['appointments_pending'] }}</div>
                    <div class="text-sm text-gray-600">Oczekujące</div>
                </div>
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="font-semibold mb-4 text-lg">Ostatnie wizyty</h3>
            <ul class="divide-y">
                @forelse($recent as $a)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $a->appointment_date->format('Y-m-d H:i') }}</span> —
                            {{ $a->patient->user->name }}
                            <span class="text-gray-400">→</span>
                            {{ $a->doctor->user->name }}
                        </div>
                        <a href="{{ route('appointments.show', $a) }}" class="text-blue-600 hover:underline text-sm">Pokaż →</a>
                    </li>
                @empty
                    <li class="text-gray-500 py-3">Brak wizyt.</li>
                @endforelse
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('doctors.create') }}" class="bg-indigo-600 text-white p-4 rounded-lg shadow hover:bg-indigo-700 text-center">
                + Dodaj lekarza
            </a>
            <a href="{{ route('patients.create') }}" class="bg-green-600 text-white p-4 rounded-lg shadow hover:bg-green-700 text-center">
                + Dodaj pacjenta
            </a>
            <a href="{{ route('specializations.create') }}" class="bg-purple-600 text-white p-4 rounded-lg shadow hover:bg-purple-700 text-center">
                + Dodaj specjalizację
            </a>
        </div>
    </div>
</x-app-layout>
