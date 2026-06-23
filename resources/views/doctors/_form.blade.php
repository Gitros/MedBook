@csrf

@php
    $isEdit = isset($doctor);
    $selectedSpecs = $isEdit ? $doctor->specializations->pluck('id')->toArray() : [];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Imię i nazwisko *</label>
        <input type="text" name="name" value="{{ old('name', $isEdit ? $doctor->user->name : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">E-mail *</label>
        <input type="email" name="email" value="{{ old('email', $isEdit ? $doctor->user->email : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    @if(!$isEdit)
        <div>
            <label class="block text-sm font-medium">Hasło *</label>
            <input type="password" name="password" class="mt-1 block w-full rounded border-gray-300" required>
            @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium">Nr licencji (ABC-1234567) *</label>
        <input type="text" name="license_number" value="{{ old('license_number', $isEdit ? $doctor->license_number : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('license_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Cena konsultacji (zł) *</label>
        <input type="number" step="0.01" min="0" name="consultation_fee" value="{{ old('consultation_fee', $isEdit ? $doctor->consultation_fee : '0.00') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('consultation_fee') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Gabinet</label>
        <input type="text" name="room" value="{{ old('room', $isEdit ? $doctor->room : '') }}" class="mt-1 block w-full rounded border-gray-300">
        @error('room') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4">
    <label class="block text-sm font-medium">Bio</label>
    <textarea name="bio" rows="3" class="mt-1 block w-full rounded border-gray-300">{{ old('bio', $isEdit ? $doctor->bio : '') }}</textarea>
    @error('bio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mt-4">
    <label class="block text-sm font-medium mb-2">Specjalizacje *</label>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        @foreach($specializations as $s)
            <label class="inline-flex items-center">
                <input type="checkbox" name="specializations[]" value="{{ $s->id }}"
                    {{ in_array($s->id, old('specializations', $selectedSpecs)) ? 'checked' : '' }}
                    class="rounded">
                <span class="ml-2">{{ $s->name }}</span>
            </label>
        @endforeach
    </div>
    @error('specializations') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

@if($isEdit)
    <div class="mt-4">
        <label class="inline-flex items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }} class="rounded">
            <span class="ml-2">Aktywny</span>
        </label>
    </div>
@endif

<div class="mt-6 flex gap-2">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Zapisz</button>
    <a href="{{ route('doctors.index') }}" class="px-4 py-2 bg-gray-200 rounded">Anuluj</a>
</div>
