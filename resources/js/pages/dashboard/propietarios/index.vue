<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { DataTable } from '@/components/ui/datatable';
import { ConfirmModal } from '@/components/ui/confirmmodal';
import { ref } from 'vue';

interface Propietario {
    id: number;
    tipo_documento: string;
    documento: string;
    nombre: string;
    apellido: string;
    telefono: string;
    email: string;
    direccion: string;
}

const props = defineProps<{ propietarios: Propietario[] }>();
const showDeleteModal = ref(false);
const propietarioToDelete = ref<Propietario | null>(null);

const columns = [
    { key: 'tipo_documento', label: 'Tipo de documento' },
    { key: 'documento', label: 'Documento' },
    { key: 'nombre', label: 'Nombre' },
    { key: 'apellido', label: 'Apellido' },
    { key: 'telefono', label: 'Teléfono' },
    { key: 'email', label: 'Email' },
    { key: 'direccion', label: 'Dirección' },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Propietarios', href: '/propietarios' },
];

function editar(propietario: Propietario) {
    router.get(`/propietarios/${propietario.id}/edit`);
}

function ver(propietario: Propietario) {
    router.get(`/propietarios/${propietario.id}`);
}

function eliminar(propietario: Propietario) {
    propietarioToDelete.value = propietario;
    showDeleteModal.value = true;
}

function confirmarEliminacion() {
    if (!propietarioToDelete.value) return;

    router.delete(`/propietarios/${propietarioToDelete.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            propietarioToDelete.value = null;
        },
    });
}

function cancelarEliminacion() {
    showDeleteModal.value = false;
    propietarioToDelete.value = null;
}
</script>

<template>
    <Head title="Propietarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <button
                    type="button"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
                    @click="router.get('/propietarios/create')"
                >
                    Registrar propietario
                </button>
            </div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTable :data="props.propietarios" :columns="columns" :per-page="10">
                    <template #actions="{ item }">
                        <div class="flex gap-2">
                            <button type="button" class="rounded-lg bg-slate-600 px-3 py-2 text-sm text-white hover:bg-slate-700" @click="ver(item)">
                                Ver
                            </button>
                            <button type="button" class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700" @click="editar(item)">
                                Editar
                            </button>
                            <button type="button" class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700" @click="eliminar(item)">
                                Eliminar
                            </button>
                        </div>
                    </template>
                </DataTable>

                <ConfirmModal
                    :show="showDeleteModal"
                    title="Eliminar propietario"
                    :message="propietarioToDelete ? `¿Estás seguro de que deseas eliminar a ${propietarioToDelete.nombre} ${propietarioToDelete.apellido}?` : ''"
                    @close="cancelarEliminacion"
                    @confirm="confirmarEliminacion"
                />
            </div>
        </div>
    </AppLayout>
</template>
