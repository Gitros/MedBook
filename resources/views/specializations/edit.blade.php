<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edycja: {{ $specialization->name }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('specializations.update', $specialization) }}">
                @method('PUT')
                @include('specializations._form')
            </form>
        </div>
    </div>
</x-app-layout>
