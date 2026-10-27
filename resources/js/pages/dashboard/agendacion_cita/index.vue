<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {DataTable} from "@/components/ui/datatable";
import { router } from '@inertiajs/vue3';
import {ConfirmModal} from "@/components/ui/confirmmodal";
import { ref } from 'vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'AsignacionCita',
        href: '/AsignacionCita',
    },
];

const CitasAsignadas = computed(() =>
    props.citas.map((puesto) => ({
        ...puesto,
        vehiculo: puesto.vehiculo
            ? `${puesto.vehiculo.placa} (${puesto.vehiculo.marca})`
            : 'Sin asignar',
        usuario_creador: puesto.usuario_creador
            ? `${puesto.usuario_creador.name} (${puesto.usuario_creador.rol})`
            : 'Sin asignar',
    }))
);

const props = defineProps<{
    citas: Cita[];
}>();

const columns = [
    { key: 'cita_fecha', label: 'cita_fecha' },
    { key: 'vehiculo', label: 'Vehículo' },
    { key: 'usuario_creador', label: 'Usuario Creador' },
    { key: 'tiempo_aproximado', label: 'tiempo_aproximado' },
    { key: 'estado', label: 'estado' },
    { key: 'created_at', label: 'created_at' },
];

interface Cita {
    id: number;
    cita_fecha: string;
    id_vehiculo: string;
    vehiculo: {
        id: number;
        placa: string;
        marca: string;
    } | null;
    usuario_creador: {
        id: number;
        name: string;
        rol: string;
    } | null;
    created_at: string;
}

</script>

<template>
    <Head title="Citas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <a href="/AsignacionCita/create" class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-center text-white hover:bg-blue-700">
                Agregar Cita
            </a>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTable
                    :data="CitasAsignadas"
                    :columns="columns"
                    :per-page="10"
                >
                    <template #actions="{ item }">
                        <div class="flex gap-2">

                            <button
                                type="button"
                                @click="editar(item)"
                                class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
                            >
                                Editar
                            </button>


                        </div>
                    </template>
                </DataTable>
                
            </div>
        </div>
    </AppLayout>
</template>

