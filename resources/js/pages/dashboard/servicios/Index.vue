<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { DataTable } from "@/components/ui/datatable";
import { router } from '@inertiajs/vue3';
import { ConfirmModal } from "@/components/ui/confirmmodal";
import { ref } from 'vue';

interface Servicio {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    duracion_minutos: number;
}

const props = defineProps<{
    servicios: Servicio[];
}>();

function editar(servicio: Servicio) {
    router.get(`/servicios/${servicio.id}/edit`);
}

const showDeleteModal = ref(false);
const servicioToDelete = ref<Servicio | null>(null);

function eliminar(servicio: Servicio) {
    servicioToDelete.value = servicio;
    showDeleteModal.value = true;
}

function confirmarEliminacion() {
    if (!servicioToDelete.value) return;

    router.delete(`/servicios/${servicioToDelete.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            servicioToDelete.value = null;
        },
    });
}

function cancelarEliminacion() { 
    showDeleteModal.value = false; 
    servicioToDelete.value = null; 
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Servicios',
        href: '/servicios',
    },
];

const columns = [
    { key: 'nombre', label: 'Nombre del Servicio' },
    { key: 'precio', label: 'Precio (COP)' },
    { key: 'duracion_minutos', label: 'Duración (Min)' },
    { key: 'descripcion', label: 'Descripción' },
];
</script>

<template>
    <Head title="Servicios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            
            <!-- Rejilla superior estética idéntica a Personal -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"></div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"></div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex h-full items-center justify-center p-4">
                        <Link href="/servicios/create" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                            + Registrar Servicio
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Tabla de datos nativa con acciones -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTable
                    :data="props.servicios"
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

                            <button 
                                type="button"
                                @click="eliminar(item)" 
                                class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700"
                            >
                                Eliminar
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </AppLayout>

    <!-- Modal de confirmación nativo del proyecto -->
    <ConfirmModal
        :show="showDeleteModal"
        title="¿Eliminar Servicio?"
        message="Esta acción no se puede deshacer. Se removerá el servicio del catálogo permanentemente."
        @confirm="confirmarEliminacion"
        @cancel="cancelarEliminacion"
    />
</template>
