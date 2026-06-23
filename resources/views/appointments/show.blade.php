<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Wizyta {{ $appointment->appointment_date->format('Y-m-d H:i') }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <div><strong>Lekarz:</strong> {{ $appointment->doctor->user->name }} ({{ $appointment->doctor->room ?? '—' }})</div>
            <div><strong>Pacjent:</strong> {{ $appointment->patient->user->name }}</div>
            <div><strong>Specjalizacja:</strong> {{ $appointment->specialization->name ?? '—' }}</div>
            <div><strong>Status:</strong> {{ ['pending'=>'Oczekująca','confirmed'=>'Potwierdzona','completed'=>'Zakończona','cancelled'=>'Odwołana'][$appointment->status] ?? $appointment->status }}</div>
            <div><strong>Powód:</strong> {{ $appointment->reason }}</div>
            <div><strong>Notatki:</strong> {{ $appointment->notes ?? '—' }}</div>
            <div class="flex gap-2 pt-4">
                <a href="{{ route('appointments.pdf', $appointment) }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">⬇ PDF</a>
                <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:underline self-center">← Wróć</a>
            </div>
        </div>
    </div>
</x-app-layout>
