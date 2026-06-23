<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $specialization->name }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <div>
                <h3 class="font-semibold">Opis</h3>
                <p class="text-gray-700">{{ $specialization->description ?: '—' }}</p>
            </div>
            <div>
                <h3 class="font-semibold">Status</h3>
                <p>{{ $specialization->is_active ? 'Aktywna' : 'Nieaktywna' }}</p>
            </div>
            <div>
                <h3 class="font-semibold">Lekarze ({{ $specialization->doctors->count() }})</h3>
                <ul class="list-disc ml-6">
                    @forelse($specialization->doctors as $d)
                        <li>{{ $d->user->name }} — gab. {{ $d->room ?? '—' }}</li>
                    @empty
                        <li class="text-gray-500">Brak przypisanych lekarzy.</li>
                    @endforelse
                </ul>
            </div>
            <a href="{{ route('specializations.index') }}" class="text-blue-600 hover:underline">← Wróć</a>
        </div>
    </div>
</x-app-layout>
