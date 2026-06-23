<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $patient->user->name }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <div class="flex items-center gap-4 pb-4 border-b mb-4">
                <x-avatar :name="$patient->user->name" size="xl" />
                <div>
                    <div class="text-xl font-bold">{{ $patient->user->name }}</div>
                    <div class="text-gray-600">Pacjent</div>
                </div>
            </div>
            <div><strong>E-mail:</strong> {{ $patient->user->email }}</div>
            <div><strong>PESEL:</strong> {{ $patient->pesel }}</div>
            <div><strong>Telefon:</strong> {{ $patient->phone }}</div>
            <div><strong>Data urodzenia:</strong> {{ $patient->birth_date->format('Y-m-d') }}</div>
            <div><strong>Adres:</strong> {{ $patient->address ?? '—' }}</div>
            <div><strong>Status:</strong> {{ $patient->is_active ? 'Aktywny' : 'Nieaktywny' }}</div>

            <h3 class="font-semibold mt-6">Historia wizyt</h3>
            <ul class="list-disc ml-6">
                @forelse($patient->appointments->sortByDesc('appointment_date') as $a)
                    <li>{{ $a->appointment_date->format('Y-m-d H:i') }} — {{ $a->doctor->user->name }} ({{ $a->status }})</li>
                @empty
                    <li class="text-gray-500">Brak wizyt.</li>
                @endforelse
            </ul>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('patients.edit', $patient) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edytuj</a>
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">← Wróć</a>
            </div>
        </div>
    </div>
</x-app-layout>
