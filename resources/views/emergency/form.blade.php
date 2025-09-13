<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👤 Contacto de Emergencia
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <form action="{{ route('emergencia.contacto.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Nombre</label>
                <input type="text" name="name" 
                       value="{{ old('name', $contact->name ?? '') }}"
                       class="w-full px-3 py-2 border rounded-xl dark:bg-gray-700 dark:text-white" required>
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Relación</label>
                <input type="text" name="relationship" 
                       value="{{ old('relationship', $contact->relationship ?? '') }}"
                       class="w-full px-3 py-2 border rounded-xl dark:bg-gray-700 dark:text-white" required>
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Teléfono</label>
                <input type="text" name="phone" 
                       value="{{ old('phone', $contact->phone ?? '') }}"
                       class="w-full px-3 py-2 border rounded-xl dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Correo</label>
                <input type="email" name="email" 
                       value="{{ old('email', $contact->email ?? '') }}"
                       class="w-full px-3 py-2 border rounded-xl dark:bg-gray-700 dark:text-white">
            </div>

            <button type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-xl shadow-lg transition">
                💾 Guardar Contacto
            </button>
        </form>
    </div>
</x-app-layout>
