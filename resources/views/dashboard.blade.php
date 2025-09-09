<x-app-layout>
    @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 10000)" 
        x-show="show" 
        class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 shadow transition duration-500"
        x-transition
    >
        {{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col md:flex-row h-screen p-4 gap-4" x-data="mainDashboard()">
        <!-- Emergencia -->
        <div class="flex-1 flex items-center justify-center rounded-2xl shadow-md">
            <form action="{{ route('emergencia') }}" method="GET" class="w-full h-full flex items-center justify-center">
                <button type="submit" 
                        class="w-11/12 h-5/6 bg-red-600 text-white text-4xl font-bold rounded-2xl shadow-lg hover:bg-red-700 transition flex items-center justify-center">
                    🚨 Emergencia
                </button>
            </form>
        </div>

        <!-- Recordatorios -->
        <div class="flex-1 flex flex-col gap-4">
            <!-- Botones de Citas -->
            <div class="flex-1 flex gap-4">
                <!-- Ver citas -->
                <button 
                    @click="openViewCitas()" 
                    class="flex-1 h-5/6 bg-blue-600 hover:bg-blue-700 text-white text-2xl font-bold rounded-2xl shadow-lg flex items-center justify-center text-center">
                    📅 Ver Citas
                </button>

                <!-- Nueva cita -->
                <button 
                    @click="openNewCita()" 
                    class="flex-1 h-5/6 bg-orange-500 hover:bg-orange-600 text-white text-2xl font-bold rounded-2xl shadow-lg flex items-center justify-center text-center">
                    ➕ Nueva Cita
                </button>
            </div>

            <!-- Botones de Medicamentos -->
            <div class="flex-1 flex gap-4">
                <!-- Ver medicamentos -->
                <button 
                    @click="openViewMeds()" 
                    class="flex-1 h-5/6 bg-purple-600 hover:bg-purple-700 text-white text-2xl font-bold rounded-2xl shadow-lg flex items-center justify-center text-center">
                    💊 Ver Medicamentos
                </button>

                <!-- Nuevo medicamento -->
                <button 
                    @click="openNewMed()" 
                    class="flex-1 h-5/6 bg-teal-600 hover:bg-teal-700 text-white text-2xl font-bold rounded-2xl shadow-lg flex items-center justify-center text-center">
                    ➕ Nuevo Medicamento
                </button>
            </div>

            <!-- Modal General -->
            <div 
                x-show="openModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                x-cloak
            >
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[80vh] p-6 flex flex-col">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200" x-text="modalTitle"></h2>
                        <button @click="closeModal()" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-3xl">&times;</button>
                    </div>

                    <!-- Toast -->
                    <div x-show="showToast" x-transition class="mb-2 p-3 bg-green-100 text-green-800 rounded shadow text-center text-lg font-semibold">
                        ✅ Guardado correctamente
                    </div>

                    <!-- Contenido dinámico -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Lista de citas -->
                        <div class="space-y-3 overflow-y-auto" x-show="showListCitas" style="max-height: calc(80vh - 140px);">
                            <template x-for="appointment in upcomingAppointments" :key="appointment.id">
                                <div class="p-4 border rounded-xl bg-gray-50 dark:bg-gray-700 shadow-sm">
                                    <h3 class="font-bold text-lg" x-text="appointment.title"></h3>
                                    <p class="text-md text-gray-600 dark:text-gray-300" x-text="appointment.date + ' ' + appointment.time + ' - ' + (appointment.location ?? '')"></p>
                                    <p class="text-gray-700 dark:text-gray-200" x-text="appointment.notes"></p>
                                </div>
                            </template>
                            <p x-show="upcomingAppointments.length === 0" class="text-gray-600 dark:text-gray-400 text-center text-lg">No tienes citas próximas.</p>
                        </div>

                        <!-- Formulario nueva cita -->
                        <div x-show="showAddCita" class="flex-1 flex flex-col overflow-y-auto">
                            <form @submit.prevent="addAppointment" class="space-y-2 mt-2">
                                <input type="text" x-model="form.title" placeholder="Título" class="w-full px-3 py-2 border rounded-xl text-lg" required>
                                <input type="date" x-model="form.date" class="w-full px-3 py-2 border rounded-xl text-lg" required>
                                <input type="time" x-model="form.time" class="w-full px-3 py-2 border rounded-xl text-lg" required>
                                <input type="text" x-model="form.location" placeholder="Ubicación" class="w-full px-3 py-2 border rounded-xl text-lg">
                                <textarea x-model="form.notes" placeholder="Notas" class="w-full px-3 py-2 border rounded-xl text-lg"></textarea>
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl w-full text-lg font-semibold">Guardar Cita</button>
                            </form>
                        </div>

                        <!-- Lista de medicamentos -->
                        <div class="space-y-3 overflow-y-auto" x-show="showListMeds" style="max-height: calc(80vh - 140px);">
                            <template x-for="med in medications" :key="med.id">
                                <div class="p-4 border rounded-xl bg-gray-50 dark:bg-gray-700 shadow-sm">
                                    <h3 class="font-bold text-lg" x-text="med.name"></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300" x-text="med.dosage + ' - ' + med.frequency"></p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300" x-text="'⏰ ' + med.time"></p>
                                    <p class="text-gray-700 dark:text-gray-200" x-text="med.notes"></p>
                                </div>
                            </template>
                            <p x-show="medications.length === 0" class="text-gray-600 dark:text-gray-400 text-center text-lg">No tienes medicamentos registrados.</p>
                        </div>

                        <!-- Formulario nuevo medicamento -->
                        <div x-show="showAddMed" class="flex-1 flex flex-col overflow-y-auto">
                            <form @submit.prevent="addMedication" class="space-y-2 mt-2">
                                <input type="text" x-model="medForm.name" placeholder="Nombre del medicamento" class="w-full px-3 py-2 border rounded-xl text-lg" required>
                                <input type="text" x-model="medForm.dosage" placeholder="Dosis (ej: 500mg)" class="w-full px-3 py-2 border rounded-xl text-lg">
                                <input type="text" x-model="medForm.frequency" placeholder="Frecuencia (ej: cada 8 horas)" class="w-full px-3 py-2 border rounded-xl text-lg">
                                <input type="time" x-model="medForm.time" class="w-full px-3 py-2 border rounded-xl text-lg" required>
                                <textarea x-model="medForm.notes" placeholder="Notas adicionales" class="w-full px-3 py-2 border rounded-xl text-lg"></textarea>
                                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-xl w-full text-lg font-semibold">Guardar Medicamento</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<script>
function mainDashboard() {
    return {
        // Modal control
        openModal: false,
        modalTitle: '',
        showToast: false,

        // Citas
        appointments: [],
        upcomingAppointments: [],
        form: { title: '', date: '', time: '', location: '', notes: '' },
        showListCitas: false,
        showAddCita: false,

        // Medicamentos
        medications: [],
        medForm: { name: '', dosage: '', frequency: '', time: '', notes: '' },
        showListMeds: false,
        showAddMed: false,

        // ---- Citas ----
        openViewCitas() {
            this.modalTitle = "📅 Mis Citas";
            this.openModal = true;
            this.showListCitas = true;
            this.showAddCita = false;
            this.showListMeds = false;
            this.showAddMed = false;

            axios.get("{{ route('appointments.index') }}")
                .then(res => {
                    this.appointments = res.data;
                    const today = new Date().toISOString().split('T')[0];
                    this.upcomingAppointments = res.data.filter(a => a.date >= today);
                })
                .catch(() => alert('Error al cargar las citas'));
        },

        openNewCita() {
            this.modalTitle = "➕ Nueva Cita";
            this.openModal = true;
            this.showListCitas = false;
            this.showAddCita = true;
            this.showListMeds = false;
            this.showAddMed = false;
        },

        addAppointment() {
            axios.post("{{ route('appointments.store') }}", this.form)
                .then(res => {
                    this.appointments.push(res.data);
                    const today = new Date().toISOString().split('T')[0];
                    if(res.data.date >= today) this.upcomingAppointments.push(res.data);

                    this.form = { title: '', date: '', time: '', location: '', notes: '' };
                    this.showToast = true;
                    setTimeout(() => { this.showToast = false }, 5000);
                })
                .catch(() => alert('Error al guardar la cita'));
        },

        // ---- Medicamentos ----
        openViewMeds() {
            this.modalTitle = "💊 Mis Medicamentos";
            this.openModal = true;
            this.showListCitas = false;
            this.showAddCita = false;
            this.showListMeds = true;
            this.showAddMed = false;

            axios.get("{{ route('medications.index') }}")
                .then(res => {
                    this.medications = res.data;
                })
                .catch(() => alert('Error al cargar los medicamentos'));
        },

        openNewMed() {
            this.modalTitle = "➕ Nuevo Medicamento";
            this.openModal = true;
            this.showListCitas = false;
            this.showAddCita = false;
            this.showListMeds = false;
            this.showAddMed = true;
        },

        addMedication() {
            axios.post("{{ route('medications.store') }}", this.medForm)
                .then(res => {
                    this.medications.push(res.data);
                    this.medForm = { name: '', dosage: '', frequency: '', time: '', notes: '' };
                    this.showToast = true;
                    setTimeout(() => { this.showToast = false }, 5000);
                })
                .catch(() => alert('Error al guardar el medicamento'));
        },

        // Cerrar modal
        closeModal() {
            this.openModal = false;
            this.showListCitas = false;
            this.showAddCita = false;
            this.showListMeds = false;
            this.showAddMed = false;
        }
    }
}
</script>
</x-app-layout>
