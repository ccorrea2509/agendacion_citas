<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import {Input} from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import {Textarea} from "@/components/ui/textarea";

const form = useForm({
    nombre: '',
    apellido: '',
    telefono: '',
    email: '',
    direccion: '',
    especialidad: '',
    horario_inicio: '',
    horario_fin: '',

});

function submit() {
    form.post('/Personal', {
        onSuccess: () => {
            form.reset();
        },
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Personal',
        href: '/personal',
    },
];

</script>

<template>
    <Head title="Personal" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <!--<form @submit.prevent="submit">-->
                
                        
                        
                <form @submit.prevent="form.post('/Personal')">   
                    <div class="p-6 md:p-2">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800 p-3">
                            Registrar Personal
                        </h2>
                        <div class="grid gap-2 p-1">
                            <Label for="nombre">Nombre:</Label>
                            <Input type="text" v-model="form.nombre" placeholder="Nombre" id="nombre" name="nombre" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="apellido">Apellido:</Label>
                            <Input type="text" v-model="form.apellido" placeholder="Apellido" id="apellido" name="apellido" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="telefono">Teléfono:</Label>
                            <Input type="text" v-model="form.telefono" placeholder="Teléfono" id="telefono" name="telefono" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="email">Email:</Label>
                            <Input type="email" v-model="form.email" placeholder="Email" id="email" name="email" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="direccion">Dirección:</Label>
                            <Input type="text" v-model="form.direccion" placeholder="Dirección" id="direccion" name="direccion" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="especialidad">Especialidad:</Label>
                            <Textarea v-model="form.especialidad" placeholder="Especialidad" id="especialidad" name="especialidad" required></Textarea>
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="horario_inicio">Horario de Inicio:</Label>
                            <Input type="time" v-model="form.horario_inicio" id="horario_inicio" name="horario_inicio" required />
                        </div>
                        <div class="grid gap-2 p-1">
                            <Label for="horario_fin">Horario de Fin:</Label>
                            <Input type="time" v-model="form.horario_fin" id="horario_fin" name="horario_fin" required />
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

