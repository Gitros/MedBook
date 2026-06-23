<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">{{ __('Pacjenci') }}</h2>
            <a href="{{ route('patients.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Dodaj pacjenta</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form method="GET" class="mb-6 flex gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Imię, e-mail, PESEL, telefon..." class="flex-1 rounded border-gray-300">
                    <select name="status" class="rounded border-gray-300">
                        <option value="">Aktywni</option>
                        <option value="inactive" @selected(request('status')==='inactive')>Nieaktywni</option>
                        <option value="all" @selected(request('status')==='all')>Wszyscy</option>
                    </select>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Szukaj</button>
                </form>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left p-2">Imię i nazwisko</th>
                            <th class="text-left p-2">E-mail</th>
                            <th class="text-left p-2">PESEL</th>
                            <th class="text-left p-2">Telefon</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-right p-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $p)
                            <tr class="border-b">
                                <td class="p-2">
                                    <div class="flex items-center gap-2">
                                        <x-avatar :name="$p->user->name" size="sm" />
                                        <span class="font-medium">{{ $p->user->name }}</span>
                                    </div>
                                </td>
                                <td class="p-2 text-sm">{{ $p->user->email }}</td>
                                <td class="p-2">{{ $p->pesel }}</td>
                                <td class="p-2">{{ $p->phone }}</td>
                                <td class="p-2">
                                    @if($p->is_active)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Aktywny</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Nieaktywny</span>
                                    @endif
                                </td>
                                <td class="p-2 text-right space-x-2">
                                    <a href="{{ route('patients.show', $p) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                                    <a href="{{ route('patients.edit', $p) }}" class="text-yellow-600 hover:underline">Edytuj</a>
                                    @if($p->is_active)
                                        <form method="POST" action="{{ route('patients.destroy', $p) }}" class="inline" onsubmit="return confirm('Dezaktywować pacjenta?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">Dezaktywuj</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">Brak pacjentów.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $patients->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
