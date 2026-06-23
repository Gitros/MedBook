<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">{{ __('Wizyty') }}</h2>
            <div class="flex gap-2">
                @if(auth()->user()->isPatient() || auth()->user()->isAdmin())
                    <a href="{{ route('appointments.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Nowa wizyta</a>
                @endif
                @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                    <a href="{{ route('appointments.export', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded">⬇ CSV</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Powód..." class="rounded border-gray-300">
                    <select name="status" class="rounded border-gray-300">
                        <option value="">Wszystkie statusy</option>
                        @foreach(['pending'=>'Oczekująca', 'confirmed'=>'Potwierdzona', 'completed'=>'Zakończona', 'cancelled'=>'Odwołana'] as $k=>$v)
                            <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="from" value="{{ request('from') }}" class="rounded border-gray-300">
                    <input type="date" name="to" value="{{ request('to') }}" class="rounded border-gray-300">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Szukaj</button>
                </form>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left p-2">Data</th>
                            <th class="text-left p-2">Lekarz</th>
                            <th class="text-left p-2">Pacjent</th>
                            <th class="text-left p-2">Powód</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-right p-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $a)
                            <tr class="border-b">
                                <td class="p-2">{{ $a->appointment_date->format('Y-m-d H:i') }}</td>
                                <td class="p-2">{{ $a->doctor->user->name }}</td>
                                <td class="p-2">{{ $a->patient->user->name }}</td>
                                <td class="p-2 text-sm">{{ Str::limit($a->reason, 40) }}</td>
                                <td class="p-2">
                                    @php
                                        $cls = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ][$a->status] ?? 'bg-gray-100 text-gray-800';
                                        $labels = ['pending'=>'Oczekująca','confirmed'=>'Potwierdzona','completed'=>'Zakończona','cancelled'=>'Odwołana'];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded {{ $cls }}">{{ $labels[$a->status] ?? $a->status }}</span>
                                </td>
                                <td class="p-2 text-right space-x-2">
                                    <a href="{{ route('appointments.show', $a) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                                    @if($a->status !== 'cancelled' && $a->status !== 'completed')
                                        <a href="{{ route('appointments.edit', $a) }}" class="text-yellow-600 hover:underline">Edytuj</a>
                                        <form method="POST" action="{{ route('appointments.destroy', $a) }}" class="inline" onsubmit="return confirm('Odwołać wizytę?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">Odwołaj</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">Brak wizyt.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $appointments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
