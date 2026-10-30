<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

interface Vehiculo {
    id: number;
    placa: string;
    linea: string;
    marca: string;
    modelo: string;
    created_at: string;
}

interface Propietario {
    id: number;
    tipo_documento: string;
    documento: string;
    nombre: string;
    apellido: string;
    telefono: string;
    email: string;
    direccion: string;
    vehiculos: Vehiculo[];
}

const props = defineProps<{ propietario: Propietario }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Propietarios', href: '/propietarios' },
    { title: `${props.propietario.nombre} ${props.propietario.apellido}`, href: `/propietarios/${props.propietario.id}` },
];

function fechaRegistro(fecha: string) {
    return new Date(fecha).toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
</script>

<template>
    <Head :title="`Propietario: ${props.propietario.nombre} ${props.propietario.apellido}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-5 p-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Detalle del propietario</h1>
                    <p class="text-sm text-muted-foreground">Información y vehículos actualmente asignados.</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50" @click="router.get('/propietarios')">
                        Volver
                    </button>
                    <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700" @click="router.get(`/propietarios/${props.propietario.id}/edit`)">
                        Editar propietario
                    </button>
                </div>
            </div>

            <section class="rounded-xl border bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold">Datos del propietario</h2>
                <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm text-muted-foreground">Nombre</dt>
                        <dd class="font-medium">{{ props.propietario.nombre }} {{ props.propietario.apellido }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-muted-foreground">Documento</dt>
                        <dd class="font-medium">{{ props.propietario.tipo_documento }} {{ props.propietario.documento }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-muted-foreground">Teléfono</dt>
                        <dd class="font-medium">{{ props.propietario.telefono }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-muted-foreground">Correo</dt>
                        <dd class="font-medium">{{ props.propietario.email }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm text-muted-foreground">Dirección</dt>
                        <dd class="font-medium">{{ props.propietario.direccion }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-xl border bg-white p-5 shadow-sm">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold">Vehículos actualmente asignados</h2>
                        <p class="text-sm text-muted-foreground">{{ props.propietario.vehiculos.length }} vehículo(s)</p>
                    </div>
                    <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700" @click="router.get('/vehiculos/create')">
                        Registrar vehículo
                    </button>
                </div>

                <div v-if="props.propietario.vehiculos.length" class="overflow-x-auto">
                    <table class="w-full min-w-[600px] text-left text-sm">
                        <thead class="border-b bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3">Placa</th>
                                <th class="px-4 py-3">Marca</th>
                                <th class="px-4 py-3">Línea</th>
                                <th class="px-4 py-3">Modelo</th>
                                <th class="px-4 py-3">Registrado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="vehiculo in props.propietario.vehiculos" :key="vehiculo.id" class="border-b last:border-0">
                                <td class="px-4 py-3 font-medium">{{ vehiculo.placa }}</td>
                                <td class="px-4 py-3">{{ vehiculo.marca }}</td>
                                <td class="px-4 py-3">{{ vehiculo.linea }}</td>
                                <td class="px-4 py-3">{{ vehiculo.modelo }}</td>
                                <td class="px-4 py-3">{{ fechaRegistro(vehiculo.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p v-else class="rounded-lg bg-gray-50 px-4 py-8 text-center text-sm text-muted-foreground">
                    Este propietario todavía no tiene vehículos asignados.
                </p>
            </section>
        </div>
    </AppLayout>
</template>
