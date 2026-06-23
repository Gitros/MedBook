<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Panel lekarza — {{ auth()->user()->name }}</h2></x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow rounded p-6">
            <h3 class="font-semibold mb-4">Dziś ({{ today()->format('Y-m-d') }})</h3>
            @if($today->isEmpty())
                <p class="text-gray-500">Brak wizyt na dziś.</p>
            @else
                <ul class="divide-y">
                    @foreach($today as $a)
                        <li class="py-2 flex justify-between items-center">
                            <span>
                                <strong>{{ $a->appointment_date->format('H:i') }}</strong> —
                                {{ $a->patient->user->name }}
                                <span class="ml-2 text-xs px-2 py-1 bg-gray-100 rounded">{{ $a->status }}</span>
                            </span>
                            <a href="{{ route('appointments.show', $a) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white shadow rounded p-6">
            <h3 class="font-semibold mb-4">Nadchodzące</h3>
            @if($upcoming->isEmpty())
                <p class="text-gray-500">Brak nadchodzących wizyt.</p>
            @else
                <ul class="divide-y">
                    @foreach($upcoming as $a)
                        <li class="py-2 flex justify-between">
                            <span>{{ $a->appointment_date->format('Y-m-d H:i') }} — {{ $a->patient->user->name }}</span>
                            <a href="{{ route('appointments.show', $a) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
