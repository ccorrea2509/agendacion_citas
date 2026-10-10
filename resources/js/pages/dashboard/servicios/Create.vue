<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

const serviciosPredefinidos = [
    { nombre: 'Cambio de Aceite y Filtro (10W-30 Sintético)', precio: 180000, duracion: 40, descripcion: 'Cambio de aceite de motor sintético, filtro de aceite estándar y mano de obra.' },
    { nombre: 'Alineación Computarizada y Balanceo', precio: 90000, duracion: 60, descripcion: 'Alineación computarizada del eje delantero/trasero y balanceo de las 4 ruedas.' },
    { nombre: 'Sincronización de Motor (4 Cilindros)', precio: 250000, duracion: 120, descripcion: 'Limpieza de inyectores por ultrasonido, cambio de bujías, filtro de aire y combustible.' },
    { nombre: 'Cambio de Pastillas de Freno Delanteras', precio: 150000, duracion: 60, descripcion: 'Instalación de pastillas de freno homologadas y revisión de líquido de frenos.' },
    { nombre: 'Diagnóstico Computarizado con Escáner', precio: 70000, duracion: 30, descripcion: 'Lectura y borrado de códigos de error en módulos OBD2 del vehículo.' }
];

const plantillaSeleccionada = ref('');

const form = useForm({
    nombre: '',
    precio: '',
    duracion_minutos: '',
    descripcion: '',
});

watch(plantillaSeleccionada, (nuevoNombre) => {
    const encontrado = serviciosPredefinidos.find(s => s.nombre === nuevoNombre);
    if (encontrado) {
        form.nombre = encontrado.nombre;
        form.precio = encontrado.precio.toString();
        form.duracion_minutos = encontrado.duracion.toString();
        form.descripcion = encontrado.descripcion;
    }
});

function submit() {
    form.post('/servicios', {
        onSuccess: () => {
            form.reset();
            plantillaSeleccionada.value = '';
        },
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Servicios', href: '/servicios' },
    { title: 'Registrar', href: '/servicios/create' },
];
</script>

<template>
    <Head title="Registrar Servicio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                
                <form @submit.prevent="submit">   
                    <div class="p-6 md:p-2">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800 dark:text-gray-100 p-3">
                            Registrar Servicio
                        </h2>

                        <div class="grid gap-2 p-1 mb-4">
                            <Label for="plantilla">Cargar Plantilla de Trabajo (Opcional):</Label>
                            <select id="plantilla" v-model="plantillaSeleccionada" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 text-white bg-[#111]">
                                <option value="" class="bg-black">-- Selecciona un trabajo estándar --</option>
                                <option v-for="item in serviciosPredefinidos" :key="item.nombre" :value="item.nombre" class="bg-black">
                                    {{ item.nombre }}
                                </option>
                            </select>
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="nombre">Nombre:</Label>
                            <Input type="text" v-model="form.nombre" placeholder="Nombre del servicio" id="nombre" name="nombre" required />
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="precio">Precio (COP):</Label>
                            <Input type="number" v-model="form.precio" placeholder="Ej. 180000" id="precio" name="precio" required />
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="duracion_minutos">Duración Estimada (Minutos):</Label>
                            <Input type="number" v-model="form.duracion_minutos" placeholder="Ej. 60" id="duracion_minutos" name="duracion_minutos" required />
                        </div>

                        <div class="grid gap-2 p-1">
                            <Label for="descripcion">Descripción:</Label>
                            <Textarea v-model="form.descripcion" placeholder="Especificaciones del servicio técnico" id="descripcion" name="descripcion" required></Textarea>
                        </div>

                        <div class="grid gap-2 p-6">
                            <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </AppLayout>
</template>
