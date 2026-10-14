<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

const props = defineProps({
    servicio: Object
});

const form = useForm({
    id: props.servicio?.id || '',
    nombre: props.servicio?.nombre || '',
    precio: props.servicio?.precio || '',
    duracion_minutos: props.servicio?.duracion_minutos || '',
    descripcion: props.servicio?.descripcion || '',
});

function submit() {
    form.put(`/Servicios/${form.id}`);
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Servicios', href: '/Servicios' },
    { title: 'Editar', href: `/Servicios/${form.id}/edit` },
];
</script>

<template>
    <Head title="Editar Servicio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                
                <form @submit.prevent="submit">   
                    <div class="p-6 md:p-2">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800 dark:text-gray-100 p-3">
                            Editar Servicio
                        </h2>

                        <div class="grid gap-2 p-1">
                            <Label for="nombre">Nombre:</Label>
                            <Input type="text" v-model="form.nombre" placeholder="Nombre" id="nombre" name="nombre" required />
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="precio">Precio (COP):</Label>
                            <Input type="number" v-model="form.precio" placeholder="Precio" id="precio" name="precio" required />
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="duracion_minutos">Duración Estimada (Minutos):</Label>
                            <Input type="number" v-model="form.duracion_minutos" placeholder="Duración" id="duracion_minutos" name="duracion_minutos" required />
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="descripcion">Descripción:</Label>
                            <Textarea v-model="form.descripcion" placeholder="Descripción" id="descripcion" name="descripcion" required></Textarea>
                        </div>

                        <div class="grid gap-2 p-6">
                            <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </AppLayout>
</template>
