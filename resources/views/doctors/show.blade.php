<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $doctor->user->name }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <div class="flex items-center gap-4 pb-4 border-b">
                <x-avatar :name="$doctor->user->name" size="xl" />
                <div>
                    <div class="text-xl font-bold">{{ $doctor->user->name }}</div>
                    <div class="text-gray-600">{{ $doctor->specializations->pluck('name')->join(', ') }}</div>
                </div>
            </div>
            <div><strong>E-mail:</strong> {{ $doctor->user->email }}</div>
            <div><strong>Licencja:</strong> {{ $doctor->license_number }}</div>
            <div><strong>Cena konsultacji:</strong> {{ number_format($doctor->consultation_fee, 2) }} zł</div>
            <div><strong>Gabinet:</strong> {{ $doctor->room ?? '—' }}</div>
            <div><strong>Status:</strong> {{ $doctor->is_active ? 'Aktywny' : 'Nieaktywny' }}</div>
            <div>
                <strong>Specjalizacje:</strong>
                @foreach($doctor->specializations as $s)
                    <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $s->name }}</span>
                @endforeach
            </div>
            <div>
                <strong>Bio:</strong>
                <p class="text-gray-700">{{ $doctor->bio ?: '—' }}</p>
            </div>
            <div class="flex gap-2 pt-4">
                @if(auth()->user()->isPatient())
                    <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Umów wizytę</a>
                @endif
                <a href="{{ route('doctors.index') }}" class="text-blue-600 hover:underline self-center">← Wróć</a>
            </div>
        </div>
    </div>
</x-app-layout>
