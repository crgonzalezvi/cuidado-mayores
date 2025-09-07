<x-app-layout>
    <div class="flex flex-col md:flex-row h-screen">
        <!-- Botón de Emergencia -->
        <div class="flex-1 flex items-center justify-center bg-gray-100">
            <!-- Botón de emergencia -->
<form action="{{ route('emergencia') }}" method="GET">
    <button type="submit" class="w-full h-48 bg-red-600 text-white text-2xl font-bold rounded-xl shadow-lg hover:bg-red-700 transition">
        🚨 Emergencia
    </button>
</form>
        </div>

        <!-- Sección Recordatorios -->
        <div class="flex-1 flex flex-col">
            <!-- Botón de Citas -->
            <div class="flex-1 flex items-center justify-center bg-blue-100 border-b-2 border-white">
                <button class="bg-blue-600 hover:bg-blue-700 text-white text-2xl font-bold py-12 px-10 rounded-2xl shadow-lg w-5/6 h-5/6 flex items-center justify-center text-center">
                    📅 Recordatorio de Citas
                </button>
            </div>

            <!-- Botón de Medicamentos -->
            <div class="flex-1 flex items-center justify-center bg-green-100">
                <button class="bg-green-600 hover:bg-green-700 text-white text-2xl font-bold py-12 px-10 rounded-2xl shadow-lg w-5/6 h-5/6 flex items-center justify-center text-center">
                    💊 Recordatorio de Medicamentos
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
