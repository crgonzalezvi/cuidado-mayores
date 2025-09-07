<x-guest-layout>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">
            Crear una cuenta
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nombre -->
            <div class="mb-5">
                <label for="name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Nombre completo
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="mt-2 w-full px-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500" />
            </div>

            <!-- Correo -->
            <div class="mb-5">
                <label for="email" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Correo electrónico
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="mt-2 w-full px-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <!-- Contraseña -->
            <div class="mb-5">
                <label for="password" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Contraseña
                </label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="mt-2 w-full px-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-5">
                <label for="password_confirmation" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Confirmar contraseña
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="mt-2 w-full px-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
            </div>

            <!-- Botones -->
            <div class="flex flex-col space-y-4">
                <button type="submit"
                    class="w-full py-3 text-xl font-bold text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-400">
                    Registrarme
                </button>

                <a href="{{ route('login') }}"
                    class="w-full py-3 text-xl font-semibold text-center text-indigo-600 bg-gray-100 rounded-xl shadow hover:bg-gray-200 dark:bg-gray-700 dark:text-indigo-400 dark:hover:bg-gray-600">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
