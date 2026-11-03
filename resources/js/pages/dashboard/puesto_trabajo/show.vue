<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Tecnico {
    id: number;
    nombre: string;
    apellido: string;
}

interface PuestoTrabajo {
    id: number;
    numero_puesto: string;
    id_servicios: string;
    tecnico: Tecnico | null;
    created_at: string;
}

interface Servicio {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number | string;
    duracion_minutos: number | string;
}

const props = defineProps<{
    puesto_trabajo: PuestoTrabajo;
    serviciosAsignados: Servicio[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Puestos de Trabajo', href: '/PuestoTrabajo' },
    { title: 'Detalle', href: `/PuestoTrabajo/${props.puesto_trabajo.id}` },
];
</script>

<template>
    <Head title="Detalle Puesto de Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6 bg-white dark:bg-zinc-900">
                
                <!-- Encabezado -->
                <div class="flex items-center justify-between border-b border-sidebar-border pb-4 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        Estación N° {{ puesto_trabajo.numero_puesto }}
                    </h2>
                    <Link 
                        href="/PuestoTrabajo" 
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition"
                    >
                        Volver al listado
                    </Link>
                </div>

                <!-- Información General (Puesto y Técnico) -->
                <div class="grid gap-6 md:grid-cols-2 mb-8 bg-zinc-50 dark:bg-zinc-950 p-4 rounded-lg border border-sidebar-border/40">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Número de Puesto</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1 block">
                            Puesto {{ puesto_trabajo.numero_puesto }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Técnico Asignado</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1 block">
                            <span v-if="puesto_trabajo.tecnico">
                                {{ puesto_trabajo.tecnico.nombre }} {{ puesto_trabajo.tecnico.apellido }}
                            </span>
                            <span v-else class="text-gray-400 italic">Sin empleado asignado</span>
                        </span>
                    </div>
                </div>

                <!-- Sección de Servicios Asignados (Detalle Completo) -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">
                        Servicios habilitados para este puesto de trabajo
                    </h3>

                    <div v-if="serviciosAsignados.length === 0" class="text-center py-8 text-gray-400 text-sm border border-dashed border-sidebar-border rounded-lg">
                        Este puesto no cuenta con servicios asociados válidos en su registro.
                    </div>

                    <div v-else class="overflow-x-auto rounded-lg border border-sidebar-border">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-zinc-950 border-b border-sidebar-border text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                    <th class="p-4">ID</th>
                                    <th class="p-4">Nombre del Servicio</th>
                                    <th class="p-4">Descripción</th>
                                    <th class="p-4">Duración</th>
                                    <th class="p-4 text-right">Precio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-sidebar-border/40 text-sm text-gray-600 dark:text-gray-300">
                                <tr v-for="servicio in serviciosAsignados" :key="servicio.id" class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/40 transition">
                                    <td class="p-4 font-mono text-xs text-gray-400">#{{ servicio.id }}</td>
                                    <td class="p-4 font-medium text-gray-900 dark:text-gray-100">{{ servicio.nombre }}</td>
                                    <td class="p-4 text-gray-500">{{ servicio.descripcion || 'Sin descripción' }}</td>
                                    <td class="p-4">{{ servicio.duracion_minutos ? `${servicio.duracion_minutos} Min` : 'N/A' }}</td>
                                    <td class="p-4 text-right font-semibold text-blue-600 dark:text-blue-400">${{ servicio.precio }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>