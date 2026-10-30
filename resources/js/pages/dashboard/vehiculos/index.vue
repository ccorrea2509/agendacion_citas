<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { DataTable } from '@/components/ui/datatable';
import { ConfirmModal } from '@/components/ui/confirmmodal';
import { ref } from 'vue';

interface Vehiculo {
    id: number;
    placa: string;
    linea: string;
    marca: string;
    modelo: string;
    propietario_id: number;
    propietario_nombre: string;
    propietario_documento: string;
}

const props = defineProps<{ vehiculos: Vehiculo[] }>();
const vehiculoAEliminar = ref<Vehiculo | null>(null);
const mostrarConfirmacion = ref(false);

const columns = [
    { key: 'placa', label: 'Placa' },
    { key: 'marca', label: 'Marca' },
    { key: 'linea', label: 'Línea' },
    { key: 'modelo', label: 'Modelo' },
    { key: 'propietario_nombre', label: 'Propietario' },
    { key: 'propietario_documento', label: 'Documento' },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Vehículos', href: '/vehiculos' },
];

function editar(vehiculo: Vehiculo) {
    router.get(`/vehiculos/${vehiculo.id}/edit`);
}

function verPropietario(vehiculo: Vehiculo) {
    router.get(`/propietarios/${vehiculo.propietario_id}`);
}

function confirmarEliminacion() {
    if (!vehiculoAEliminar.value) return;

    router.delete(`/vehiculos/${vehiculoAEliminar.value.id}`, {
        onSuccess: () => {
            mostrarConfirmacion.value = false;
            vehiculoAEliminar.value = null;
        },
    });
}
</script>

<template>
    <Head title="Vehículos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700" @click="router.get('/vehiculos/create')">
                    Registrar vehículo
                </button>
            </div>

            <div class="relative flex-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <DataTable :data="props.vehiculos" :columns="columns" :per-page="10">
                    <template #actions="{ item }">
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="rounded-lg bg-slate-600 px-3 py-2 text-sm text-white hover:bg-slate-700" @click="verPropietario(item)">
                                Ver propietario
                            </button>
                            <button type="button" class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700" @click="editar(item)">
                                Editar
                            </button>
                            <button type="button" class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700" @click="vehiculoAEliminar = item; mostrarConfirmacion = true">
                                Eliminar
                            </button>
                        </div>
                    </template>
                </DataTable>

                <ConfirmModal
                    :show="mostrarConfirmacion"
                    title="Eliminar vehículo"
                    :message="vehiculoAEliminar ? `¿Deseas eliminar el vehículo ${vehiculoAEliminar.placa}?` : ''"
                    @close="mostrarConfirmacion = false; vehiculoAEliminar = null"
                    @confirm="confirmarEliminacion"
                />
            </div>
        </div>
    </AppLayout>
</template>
