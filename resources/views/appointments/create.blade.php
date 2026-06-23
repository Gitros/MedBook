<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Rezerwacja wizyty</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium">Lekarz *</label>
                    <select name="doctor_id" class="mt-1 block w-full rounded border-gray-300" required>
                        <option value="">— wybierz —</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" @selected(old('doctor_id', $preselectedDoctor)==$d->id)>
                                {{ $d->user->name }} — {{ $d->specializations->pluck('name')->join(', ') }} ({{ number_format($d->consultation_fee, 2) }} zł)
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Specjalizacja (opcjonalnie)</label>
                    <select name="specialization_id" class="mt-1 block w-full rounded border-gray-300">
                        <option value="">—</option>
                        @foreach($specializations as $s)
                            <option value="{{ $s->id }}" @selected(old('specialization_id')==$s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Termin *</label>
                    <input type="datetime-local" name="appointment_date" value="{{ old('appointment_date') }}" class="mt-1 block w-full rounded border-gray-300" required>
                    @error('appointment_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Powód wizyty *</label>
                    <textarea name="reason" rows="3" class="mt-1 block w-full rounded border-gray-300" required>{{ old('reason') }}</textarea>
                    @error('reason') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Zarezerwuj</button>
                    <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded">Anuluj</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
