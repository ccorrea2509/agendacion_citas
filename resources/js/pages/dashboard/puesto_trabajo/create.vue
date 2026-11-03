<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

interface Personal {
    id: number;
    nombre: string;
    apellido: string;
}

interface Servicio {
    id: number;
    nombre: string;
    precio: number;
    duracion_minutos: number;
}

const props = defineProps<{
    personals: Personal[];
    servicios: Servicio[];
}>();

const form = useForm({
    numero_puesto: '',
    servicios: [] as string[],
    servicio_manual: '',
    tecnico: '',
});

function submit() {
    form.post('/PuestoTrabajo', {
        onSuccess: () => form.reset(),
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Puestos de Trabajo', href: '/PuestoTrabajo' },
    { title: 'Registrar', href: '/PuestoTrabajo/create' },
];
</script>

<template>
    <Head title="Registrar Puesto de Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                
                <form @submit.prevent="submit" class="max-w-2xl mx-auto space-y-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                        Registrar Puesto de Trabajo
                    </h2>

                    <!-- Número de Puesto -->
                    <div class="grid gap-2">
                        <Label for="numero_puesto">Número / Identificador de Puesto:</Label>
                        <Input type="text" v-model="form.numero_puesto" placeholder="Ej. Puesto 1" id="numero_puesto" required />
                    </div>

                    <!-- Opción A: Seleccionar de los servicios creados -->
                    <div class="grid gap-2">
                        <Label>Seleccionar Servicios del Catálogo:</Label>
                        <div v-if="servicios.length === 0" class="text-sm text-gray-400 italic">
                            No hay servicios registrados en el sistema.
                        </div>
                        <div v-else class="grid grid-cols-1 gap-2 p-4 rounded-lg border border-sidebar-border bg-zinc-50 dark:bg-zinc-950 max-h-48 overflow-y-auto">
                            <div v-for="servicio in servicios" :key="servicio.id" class="flex items-center space-x-3">
                                <input 
                                    type="checkbox" 
                                    :id="'servicio_' + servicio.id" 
                                    :value="servicio.id.toString()" 
                                    v-model="form.servicios"
                                    class="rounded border-input text-blue-600 focus:ring-blue-500 h-4 w-4"
                                />
                                <label :for="'servicio_' + servicio.id" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer select-none flex justify-between w-full">
                                    <span>{{ servicio.nombre }}</span>
                                    <span class="text-gray-400 text-xs">${{ servicio.precio }} | {{ servicio.duracion_minutos }} min</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Opción B: Escribir manualmente por si no se quiere usar el catálogo -->
                    <div class="grid gap-2">
                        <Label for="servicio_manual">O Escribir Servicio Manualmente (Opcional):</Label>
                        <Input type="text" v-model="form.servicio_manual" placeholder="Ej. Mantenimiento express" id="servicio_manual" />
                    </div>

                    <!-- Técnico Asignado -->
                    <div class="grid gap-2">
                        <Label for="tecnico">Técnico Asignado:</Label>
                        <select id="tecnico" v-model="form.tecnico" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors text-white bg-[#111] focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            <option value="" class="bg-black">-- Seleccione un empleado --</option>
                            <option v-for="personal in personals" :key="personal.id" :value="personal.id" class="bg-black">
                                {{ personal.nombre }} {{ personal.apellido }}
                            </option>
                        </select>
                    </div>

                    <!-- Botón Guardar -->
                    <div>
                        <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            Guardar Puesto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </AppLayout>
</template>