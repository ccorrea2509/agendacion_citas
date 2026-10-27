<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import {Input} from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import {SelectSearch} from "@/components/ui/selectsearch";
import Disponibles from './disponibles.vue';
import { ref,watch } from 'vue';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const hoy = new Date();
const fechaActual = [
    hoy.getFullYear(),
    String(hoy.getMonth() + 1).padStart(2, '0'),
    String(hoy.getDate()).padStart(2, '0')
].join('-');

const form = useForm({
    fecha: fechaActual,
    hora: '',
    tiempo: 0,
    puesto_trabajo: null as number | null,
    servicios: [] as number[],
    vehiculo: null as number | null,
});

///////////////
interface PuestoTrabajo {
    id: number;
    numero_puesto: number;
}

const citas = ref([]);
const puestosDisponibles = ref([]);
const fechaConsultada = ref('');
const cargando = ref(false);
const error = ref('');
let solicitudActual = 0;

async function consultarDisponibles() {
    if (!form.fecha) {
        puestosDisponibles.value = [];
        return;
    }

    const solicitud = ++solicitudActual;
    const fechaSeleccionada = form.fecha;

    cargando.value = true;
    error.value = '';

    try {
        const response = await axios.get(
            route('disponibles'),
            { params: { fecha: fechaSeleccionada } }
        );

        // Ignorar respuestas de solicitudes anteriores
        if (solicitud !== solicitudActual) return;

        citas.value = response.data.citas;
        puestosDisponibles.value = response.data.puestosDisponibles;
        fechaConsultada.value = response.data.fecha;
    } catch (e) {
        if (solicitud !== solicitudActual) return;
        error.value = 'No fue posible consultar los puestos disponibles.';
        puestosDisponibles.value = [];
    } finally {
        if (solicitud === solicitudActual) {
            cargando.value = false;
        }
    }
}

////////////////////////

const tiempoAcumulado = computed(() => {
    return props.servicios
        .filter(servicio => form.servicios.includes(servicio.id))
        .reduce((total, servicio) => total + servicio.duracion_minutos, 0);
});

const tiempoFormateado = computed(() => {
    const horas = Math.floor(tiempoAcumulado.value / 60);
    const minutos = tiempoAcumulado.value % 60;

    if (horas === 0) return `${minutos} minutos`;
    if (minutos === 0) return `${horas} h`;

    return `${horas} h ${minutos} min`;
});


interface Servicio {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    duracion_minutos: number;
}

interface Vehiculos {
    id: number;
    placa: string;
}

const props = defineProps<{
    servicios: Servicio[];
    vehiculos: Vehiculos[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Citas',
        href: '/AsignacionCita',
    },
];

const horaSeleccionada = ref('08');
const minutoSeleccionado = ref('00');
const periodoSeleccionado = ref('AM');

watch(
    [horaSeleccionada, minutoSeleccionado, periodoSeleccionado],
    ([hora, minuto, periodo]) => {
        let hora24 = Number(hora);

        if (periodo === 'AM' && hora24 === 12) {
            hora24 = 0;
        } else if (periodo === 'PM' && hora24 !== 12) {
            hora24 += 12;
        }

        form.hora = `${String(hora24).padStart(2, '0')}:${minuto}`;
    },
    { immediate: true }
);


watch(tiempoAcumulado, (nuevoTiempo) => {
    form.tiempo = nuevoTiempo;
});

</script>

<template>
    <Head title="Creacion Citas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <!--<form @submit.prevent="submit">-->
                
                <form @submit.prevent="form.post('/AsignacionCita')">   
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-1 ">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800 p-3">
                            Asignacion Cita
                        </h2>
                        <p class="px-3 text-sm text-muted-foreground">Se creará asigaraa una cita lo mas cercana posible.</p>
                        <div class="">
                            
                            <Label for="Cliente">Cliente:</Label>
                            
                            <SelectSearch
                                v-model="form.vehiculo"
                                :options="props.vehiculos"
                                option-value="id"
                                option-label="placa"
                                placeholder="Buscar vehículo por placa..."
                            />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="nombre">Fecha:</Label>
                            <Input type="date" :min="fechaActual" v-model="form.fecha" @change="consultarDisponibles" required />
                            <Label for="hora">Hora:</Label>
                            <div class="grid grid-cols-3 gap-2">
                                <!-- Hora -->
                                <select
                                    v-model="horaSeleccionada"
                                    class="w-full rounded-md border border-gray-300 p-2.5"
                                >
                                    <option
                                        v-for="hora in 12"
                                        :key="hora"
                                        :value="String(hora).padStart(2, '0')"
                                    >
                                        {{ String(hora).padStart(2, '0') }}
                                    </option>
                                </select>

                                <!-- Minutos -->
                                <select
                                    v-model="minutoSeleccionado"
                                    class="w-full rounded-md border border-gray-300 p-2.5"
                                >
                                    <option value="00">00</option>
                                    <option value="30">30</option>
                                </select>

                                <!-- AM / PM -->
                                <select
                                    v-model="periodoSeleccionado"
                                    class="w-full rounded-md border border-gray-300 p-2.5"
                                >
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid gap-2 p-1">
                            <div
                                v-for="servicio in props.servicios"
                                :key="servicio.id"
                                class="flex items-start gap-3 rounded-lg border p-4 hover:bg-gray-50"
                            >
                                <input
                                    type="checkbox"
                                    :id="`servicio-${servicio.id}`"
                                    :value="servicio.id"
                                    v-model="form.servicios"
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                />

                                <label
                                    :for="`servicio-${servicio.id}`"
                                    class="flex-1 cursor-pointer"
                                >
                                    <div class="font-medium text-gray-900">
                                        {{ servicio.nombre }}
                                    </div>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ servicio.descripcion }}
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-4 text-sm">
                                        <span class="font-semibold text-green-700">
                                            ${{ servicio.precio }}
                                        </span>

                                        <span class="text-gray-500">
                                            {{ servicio.duracion_minutos }} minutos
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 rounded-xl border   p-4">
                            <div class="flex items-center gap-3 bg-blue-50">
                                <div class="rounded-lg bg-blue-100 p-3">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-blue-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                        <path
                                            stroke-linecap="round"
                                            stroke-width="2"
                                            d="M12 6v6l4 2"
                                        />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-blue-700">
                                        Tiempo acumulado de los servicios
                                    </p>

                                    <p class="text-2xl font-bold text-blue-900">
                                        {{ tiempoFormateado }}
                                    </p>
                                </div>
                            </div>
                            <div id="ver-fechas" class="mt-6">
                                <p v-if="cargando" class="text-sm text-gray-500">
                                    Consultando disponibilidad...
                                </p>

                                <p v-else-if="error" class="text-sm text-red-600">
                                    {{ error }}
                                </p>

                                <Disponibles
                                    v-else
                                    :citas="citas"
                                    :fecha="fechaConsultada"
                                    :puestos-disponibles="puestosDisponibles"
                                    @seleccionar="form.puesto_trabajo = $event"
                                />
                            </div>
                        </div>
                        <div class=" gap-2 p-6">
                            <button type="submit" class="rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </AppLayout>
</template>

