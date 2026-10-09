<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {DataTable} from "@/components/ui/datatable";
import { router } from '@inertiajs/vue3';
import {ConfirmModal} from "@/components/ui/confirmmodal";
import { ref } from 'vue';


function editar(personal: Personal) {
    router.get(`/Personal/${personal.id}/edit`);
}

const showDeleteModal = ref(false);
const personalToDelete = ref<Personal | null>(null);

function eliminar(personal: Personal) {
    personalToDelete.value = personal;
    showDeleteModal.value = true;
}


function confirmarEliminacion() {
    if (!personalToDelete.value) {
        return;
    }

    router.delete(`/Personal/${personalToDelete.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            personalToDelete.value = null;
        },
    });
}

function cancelarEliminacion() { 
    showDeleteModal.value = false; 
    personalToDelete.value = null; 
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Personal',
        href: '/personal',
    },
];

const props = defineProps<{
    personals: Personal[];
}>();

const columns = [
    { key: 'nombre', label: 'Nombre' },
    { key: 'apellido', label: 'Apellido' },
    { key: 'telefono', label: 'Teléfono' },
    { key: 'email', label: 'Email' },
    { key: 'direccion', label: 'Dirección' },
    { key: 'especialidad', label: 'Especialidad' },
    { key: 'horario_inicio', label: 'Inicio' },
    { key: 'horario_fin', label: 'Fin' },
];

interface Personal {
    id: number;
    nombre: string;
    apellido: string;
    telefono: string;
    email: string;
    direccion: string;
    especialidad: string;
    horario_inicio: string;
    horario_fin: string;
}

</script>

<template>
    <Head title="Personal" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                   
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTable
                    :data="props.personals"
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
                                class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700" >
                                Eliminar
                             </button>

                        </div>
                    </template>
                </DataTable>
                <ConfirmModal 
                    :show="showDeleteModal" 
                    title="Eliminar personal" 
                    :message=" personalToDelete ? `¿Estás seguro de que deseas eliminar a ${personalToDelete.nombre} ${personalToDelete.apellido}?` : '' " 
                    @close="cancelarEliminacion" 
                    @confirm="confirmarEliminacion" />
            </div>
        </div>
    </AppLayout>
</template>

