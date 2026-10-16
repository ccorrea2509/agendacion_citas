<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {DataTable} from "@/components/ui/datatable";
import { router } from '@inertiajs/vue3';
import {ConfirmModal} from "@/components/ui/confirmmodal";
import { ref } from 'vue';
import { computed } from 'vue';


function editar(puesto_trabajo: PuestosTrabajo) {
    router.get(`/PuestoTrabajo/${puesto_trabajo.id}/edit`);
}

const showDeleteModal = ref(false);
const puesto_trabajoToDelete = ref<PuestosTrabajo | null>(null);

function eliminar(puesto_trabajo: PuestosTrabajo) {
    puesto_trabajoToDelete.value = puesto_trabajo;
    showDeleteModal.value = true;
}


function confirmarEliminacion() {
    if (!puesto_trabajoToDelete.value) {
        return;
    }

    router.delete(`/PuestoTrabajo/${puesto_trabajoToDelete.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            puesto_trabajoToDelete.value = null;
        },
    });
}

function cancelarEliminacion() { 
    showDeleteModal.value = false; 
    puesto_trabajoToDelete.value = null; 
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Puestos de Trabajo',
        href: '/puestotrabajo',
    },
];

const puestosTrabajoTabla = computed(() =>
    props.puestos_trabajo.map((puesto) => ({
        ...puesto,
        tecnico: puesto.tecnico
            ? `${puesto.tecnico.nombre} ${puesto.tecnico.apellido}`
            : 'Sin asignar',
    }))
);

const props = defineProps<{
    puestos_trabajo: PuestosTrabajo[];
}>();

const columns = [
    { key: 'numero_puesto', label: 'Número' },
    { key: 'id_servicios', label: 'Servicios' },
    { key: 'tecnico', label: 'Técnico' },
    { key: 'created_at', label: 'Creación' },
];

interface PuestosTrabajo {
    id: number;
    numero_puesto: string;
    id_servicios: string;
    tecnico: {
        id: number;
        nombre: string;
        apellido: string;
    } | null;
    crated_at: string;
}

</script>

<template>
    <Head title="Puestos de Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <a href="/PuestoTrabajo/create" class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-center text-white hover:bg-blue-700">
                Agregar Puesto de Trabajo
            </a>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTable
                    :data="puestosTrabajoTabla"
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
                    title="Eliminar Puesto de Trabajo" 
                    :message=" puesto_trabajoToDelete ? `¿Estás seguro de que deseas eliminar el puesto de trabajo ${puesto_trabajoToDelete.numero_puesto}?` : '' " 
                    @close="cancelarEliminacion" 
                    @confirm="confirmarEliminacion" />
            </div>
        </div>
    </AppLayout>
</template>

