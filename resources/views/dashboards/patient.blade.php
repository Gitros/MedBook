<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Witaj, {{ auth()->user()->name }}</h2>
            <a href="{{ route('appointments.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Nowa wizyta</a>
        </div>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow rounded p-6">
            <h3 class="font-semibold mb-4">Twoje nadchodzące wizyty</h3>
            @if($upcoming->isEmpty())
                <p class="text-gray-500">Brak zaplanowanych wizyt. <a href="{{ route('doctors.index') }}" class="text-blue-600 hover:underline">Przejrzyj lekarzy</a></p>
            @else
                <ul class="divide-y">
                    @foreach($upcoming as $a)
                        <li class="py-2 flex justify-between items-center">
                            <span>
                                <strong>{{ $a->appointment_date->format('Y-m-d H:i') }}</strong> —
                                {{ $a->doctor->user->name }}
                                @if($a->specialization)
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded ml-2">{{ $a->specialization->name }}</span>
                                @endif
                                <span class="text-xs px-2 py-1 bg-gray-100 rounded ml-2">{{ $a->status }}</span>
                            </span>
                            <a href="{{ route('appointments.show', $a) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white shadow rounded p-6">
            <h3 class="font-semibold mb-4">Historia</h3>
            @if($past->isEmpty())
                <p class="text-gray-500">Brak historii.</p>
            @else
                <ul class="divide-y">
                    @foreach($past as $a)
                        <li class="py-2">{{ $a->appointment_date->format('Y-m-d H:i') }} — {{ $a->doctor->user->name }} ({{ $a->status }})</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
