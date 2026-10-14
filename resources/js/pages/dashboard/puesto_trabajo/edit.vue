<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import {Input} from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import {Select} from '@/components/ui/select';
import { ref } from 'vue';

const props = defineProps<{
    personals: Personal[];
    puestos_trabajo: PuestoTrabajo;
}>();

const form = useForm({
    id: props.puestos_trabajo?.id || '',
    id_servicios: props.puestos_trabajo?.id_servicios || '',
    numero_puesto: props.puestos_trabajo?.numero_puesto || '',
    id_tecnico: props.puestos_trabajo?.id_tecnico || null,

});

interface Personal { 
    id: number;
    nombre: string; 
    apellido: string;  }

interface PuestoTrabajo {
    id: number;
    numero_puesto: string | null;
    id_servicios: string | null;
    id_tecnico: number | null;
}

const personalSeleccionado = ref('');

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'PuestoTrabajo',
        href: '/PuestoTrabajo',
    },
];

</script>

<template>

    <Head title="Puesto Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <!--<form @submit.prevent="submit">-->
                
                        
                        
                <form @submit.prevent="form.put(`/PuestoTrabajo/${form.id}`)">   
                    <div class="p-6 md:p-2">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800 p-3">
                            Registrar Puesto de Trabajo
                        </h2>
                        <div class="grid gap-2 p-1">
                            <Label for="nombre">Numero Puesto:</Label>
                            <Input type="text" v-model="form.numero_puesto" placeholder="Numero Puesto" id="numero_puesto" name="numero_puesto" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="apellido">Servicios:</Label>
                            <Input type="text" v-model="form.id_servicios" placeholder="Servicios" id="id_servicios" name="id_servicios" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="telefono">Tecnico:</Label>
                            <Select v-model="personalSeleccionado" placeholder="Seleccione un empleado" > <option v-for="personal in props.personals" :key="personal.id" :value="String(personal.id)" > {{ personal.nombre }} {{ personal.apellido }} </option><option :value="null">Sin asignar</option> </Select>
                        </div>
                        <div class="grid gap-2 p-6">
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