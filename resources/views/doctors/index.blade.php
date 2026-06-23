<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('Lekarze') }}</h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('doctors.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">+ Dodaj lekarza</a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route('doctors.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Imię, e-mail, licencja..." class="rounded border-gray-300">
                    <select name="specialization" class="rounded border-gray-300">
                        <option value="">Wszystkie specjalizacje</option>
                        @foreach($specializations as $s)
                            <option value="{{ $s->id }}" @selected(request('specialization')==$s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="max_fee" value="{{ request('max_fee') }}" placeholder="Max cena (zł)" step="0.01" min="0" class="rounded border-gray-300">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Szukaj</button>
                </form>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left p-2">Imię i nazwisko</th>
                            <th class="text-left p-2">Licencja</th>
                            <th class="text-left p-2">Specjalizacje</th>
                            <th class="text-left p-2">Cena</th>
                            <th class="text-left p-2">Gabinet</th>
                            <th class="text-right p-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $d)
                            <tr class="border-b">
                                <td class="p-2">
                                    <div class="flex items-center gap-2">
                                        <x-avatar :name="$d->user->name" size="sm" />
                                        <span class="font-medium">{{ $d->user->name }}</span>
                                    </div>
                                </td>
                                <td class="p-2 text-sm">{{ $d->license_number }}</td>
                                <td class="p-2 text-sm">
                                    @foreach($d->specializations as $s)
                                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mr-1">{{ $s->name }}</span>
                                    @endforeach
                                </td>
                                <td class="p-2">{{ number_format($d->consultation_fee, 2) }} zł</td>
                                <td class="p-2">{{ $d->room ?? '—' }}</td>
                                <td class="p-2 text-right space-x-2">
                                    <a href="{{ route('doctors.show', $d) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('doctors.edit', $d) }}" class="text-yellow-600 hover:underline">Edytuj</a>
                                        <form method="POST" action="{{ route('doctors.destroy', $d) }}" class="inline" onsubmit="return confirm('Dezaktywować lekarza?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">Dezaktywuj</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">Brak lekarzy.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $doctors->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
