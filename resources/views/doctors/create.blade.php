<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Nowy lekarz</h2></x-slot>
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('doctors.store') }}">
                @include('doctors._form')
            </form>
        </div>
    </div>
</x-app-layout>
