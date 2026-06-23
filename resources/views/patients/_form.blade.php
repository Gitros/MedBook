@csrf
@php $isEdit = isset($patient); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Imię i nazwisko *</label>
        <input type="text" name="name" value="{{ old('name', $isEdit ? $patient->user->name : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">E-mail *</label>
        <input type="email" name="email" value="{{ old('email', $isEdit ? $patient->user->email : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    @if(!$isEdit)
        <div>
            <label class="block text-sm font-medium">Hasło *</label>
            <input type="password" name="password" class="mt-1 block w-full rounded border-gray-300" required>
            @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
    @endif
    <div>
        <label class="block text-sm font-medium">PESEL *</label>
        <input type="text" name="pesel" maxlength="11" value="{{ old('pesel', $isEdit ? $patient->pesel : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('pesel') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Telefon *</label>
        <input type="text" name="phone" value="{{ old('phone', $isEdit ? $patient->phone : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Data urodzenia *</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', $isEdit ? $patient->birth_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded border-gray-300" required>
        @error('birth_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4">
    <label class="block text-sm font-medium">Adres</label>
    <input type="text" name="address" value="{{ old('address', $isEdit ? $patient->address : '') }}" class="mt-1 block w-full rounded border-gray-300">
    @error('address') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

@if($isEdit && auth()->user()->isAdmin())
    <div class="mt-4">
        <label class="inline-flex items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $patient->is_active) ? 'checked' : '' }} class="rounded">
            <span class="ml-2">Aktywny</span>
        </label>
    </div>
@endif

<div class="mt-6 flex gap-2">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Zapisz</button>
    <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-200 rounded">Anuluj</a>
</div>
