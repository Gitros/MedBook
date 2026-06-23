<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Specjalizacje') }}
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('specializations.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        + Dodaj
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route('specializations.index') }}" class="mb-6 flex gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Szukaj nazwy lub opisu..." class="flex-1 rounded border-gray-300">
                    <select name="status" class="rounded border-gray-300">
                        <option value="">Wszystkie</option>
                        <option value="active" @selected(request('status')==='active')>Aktywne</option>
                        <option value="inactive" @selected(request('status')==='inactive')>Nieaktywne</option>
                    </select>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Szukaj</button>
                </form>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left p-2">Nazwa</th>
                            <th class="text-left p-2">Opis</th>
                            <th class="text-left p-2">Lekarze</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-right p-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specializations as $s)
                            <tr class="border-b">
                                <td class="p-2 font-medium">{{ $s->name }}</td>
                                <td class="p-2 text-sm text-gray-600">{{ Str::limit($s->description, 60) }}</td>
                                <td class="p-2">{{ $s->doctors()->count() }}</td>
                                <td class="p-2">
                                    @if($s->is_active)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Aktywna</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Nieaktywna</span>
                                    @endif
                                </td>
                                <td class="p-2 text-right space-x-2">
                                    <a href="{{ route('specializations.show', $s) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('specializations.edit', $s) }}" class="text-yellow-600 hover:underline">Edytuj</a>
                                        @if($s->is_active)
                                            <form method="POST" action="{{ route('specializations.destroy', $s) }}" class="inline" onsubmit="return confirm('Dezaktywować?')">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:underline">Dezaktywuj</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-4 text-center text-gray-500">Brak wyników.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $specializations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
