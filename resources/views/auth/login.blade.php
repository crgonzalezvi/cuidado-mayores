<x-guest-layout>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">
            Iniciar sesión
        </h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Correo -->
            <div class="mb-5">
                <label for="email" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Correo electrónico
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="mt-2 w-full px-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <!-- Contraseña -->
            <div class="mb-5">
                <label for="password" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Contraseña
                </label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-2 w-full px-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
            </div>

            <!-- Recordarme -->
            <div class="block mb-5">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Recordarme</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex flex-col space-y-4">
                <button type="submit"
                    class="w-full py-3 text-xl font-bold text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-400">
                    Iniciar sesión
                </button>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="w-full py-3 text-xl font-semibold text-center text-indigo-600 bg-gray-100 rounded-xl shadow hover:bg-gray-200 dark:bg-gray-700 dark:text-indigo-400 dark:hover:bg-gray-600">
                        ¿No tienes cuenta? Regístrate
                    </a>
                @endif

                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 dark:text-gray-400 underline hover:text-gray-900 dark:hover:text-gray-100 text-center mt-2" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
        </form>
    </div>
</x-guest-layout>
