@csrf

<div class="mb-4">
    <label class="block text-sm font-medium">Nazwa *</label>
    <input type="text" name="name" value="{{ old('name', $specialization->name ?? '') }}" class="mt-1 block w-full rounded border-gray-300" required>
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Opis</label>
    <textarea name="description" rows="4" class="mt-1 block w-full rounded border-gray-300">{{ old('description', $specialization->description ?? '') }}</textarea>
    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="inline-flex items-center">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $specialization->is_active ?? true) ? 'checked' : '' }} class="rounded">
        <span class="ml-2">Aktywna</span>
    </label>
</div>

<div class="flex gap-2">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Zapisz</button>
    <a href="{{ route('specializations.index') }}" class="px-4 py-2 bg-gray-200 rounded">Anuluj</a>
</div>
