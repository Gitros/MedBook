<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edycja wizyty</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                @csrf @method('PUT')

                <div class="mb-4 p-3 bg-gray-100 rounded">
                    <div><strong>Lekarz:</strong> {{ $appointment->doctor->user->name }}</div>
                    <div><strong>Pacjent:</strong> {{ $appointment->patient->user->name }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Termin *</label>
                    <input type="datetime-local" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded border-gray-300">
                    @error('appointment_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Status *</label>
                    <select name="status" class="mt-1 block w-full rounded border-gray-300" required>
                        @foreach(['pending'=>'Oczekująca', 'confirmed'=>'Potwierdzona', 'completed'=>'Zakończona', 'cancelled'=>'Odwołana'] as $k=>$v)
                            <option value="{{ $k }}" @selected(old('status', $appointment->status)===$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                @if(auth()->user()->isDoctor() || auth()->user()->isAdmin())
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Notatki lekarza</label>
                        <textarea name="notes" rows="4" class="mt-1 block w-full rounded border-gray-300">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Zapisz</button>
                    <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded">Anuluj</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
