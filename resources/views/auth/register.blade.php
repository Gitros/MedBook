<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Załóż konto pacjenta</h1>
        <p class="text-sm text-gray-500 mt-1">Konta lekarzy tworzy administrator.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" value="Imię i nazwisko" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="password" value="Hasło" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="password_confirmation" value="Powtórz hasło" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="pesel" value="PESEL" />
                <x-text-input id="pesel" class="block mt-1 w-full" type="text" name="pesel" :value="old('pesel')" maxlength="11" required />
                <x-input-error :messages="$errors->get('pesel')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="phone" value="Telefon" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="birth_date" value="Data urodzenia" />
                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" required />
                <x-input-error :messages="$errors->get('birth_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="address" value="Adres" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>
        </div>

        <button type="submit" class="mt-6 w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition">
            Zarejestruj się
        </button>

        <p class="mt-6 text-center text-sm text-gray-600">
            Masz już konto?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Zaloguj się</a>
        </p>
    </form>
</x-guest-layout>
