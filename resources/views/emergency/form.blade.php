<x-app-layout>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold text-red-600 mb-4">🚨 Configurar Contacto de Emergencia</h1>

        <form method="POST" action="{{ route('emergencia.contacto.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700">Relación (ej: hijo, hermana, vecino)</label>
                <input type="text" name="relationship" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700">Teléfono</label>
                <input type="text" name="phone" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700">Correo electrónico</label>
                <input type="email" name="email" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                Guardar Contacto
            </button>
        </form>
    </div>
</x-app-layout>
