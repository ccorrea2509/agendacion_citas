<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Propietario {
    id: number;
    nombre: string;
    apellido: string;
    documento: string;
}

interface Vehiculo {
    id: number;
    placa: string;
    linea: string;
    marca: string;
    modelo: string;
    id_propietario: number;
}

const props = defineProps<{ vehiculo: Vehiculo; propietarios: Propietario[] }>();
const busquedaPropietario = ref('');
const form = useForm({
    placa: props.vehiculo.placa,
    linea: props.vehiculo.linea,
    marca: props.vehiculo.marca,
    modelo: props.vehiculo.modelo,
    id_propietario: String(props.vehiculo.id_propietario),
});

const propietarioSeleccionado = computed(() =>
    props.propietarios.find((propietario) => propietario.id === Number(form.id_propietario)),
);

const propietariosFiltrados = computed(() => {
    const termino = busquedaPropietario.value.trim().toLocaleLowerCase();
    if (!termino) return [];

    return props.propietarios
        .filter((propietario) => `${propietario.nombre} ${propietario.apellido} ${propietario.documento}`.toLocaleLowerCase().includes(termino))
        .slice(0, 8);
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Vehículos', href: '/vehiculos' },
    { title: 'Editar', href: `/vehiculos/${props.vehiculo.id}/edit` },
];

function seleccionarPropietario(propietario: Propietario) {
    form.id_propietario = String(propietario.id);
    busquedaPropietario.value = '';
}

function cambiarPropietario() {
    form.id_propietario = '';
}

function submit() {
    form.put(`/vehiculos/${props.vehiculo.id}`);
}
</script>

<template>
    <Head title="Editar vehículo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <div class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border md:p-8">
                <form class="mx-auto max-w-3xl space-y-5" @submit.prevent="submit">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Editar vehículo</h1>
                        <p class="mt-1 text-sm text-muted-foreground">Actualiza sus datos o asígnalo a otro propietario.</p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="buscar_propietario">Propietario</Label>
                        <div v-if="propietarioSeleccionado" class="flex items-center justify-between rounded-lg border bg-gray-50 px-4 py-3">
                            <span class="font-medium">{{ propietarioSeleccionado.nombre }} {{ propietarioSeleccionado.apellido }} — {{ propietarioSeleccionado.documento }}</span>
                            <button type="button" class="text-sm text-blue-700 hover:underline" @click="cambiarPropietario">Cambiar</button>
                        </div>
                        <template v-else>
                            <Input id="buscar_propietario" v-model="busquedaPropietario" type="search" placeholder="Buscar por nombre o documento" autocomplete="off" />
                            <div v-if="busquedaPropietario.trim()" class="max-h-56 overflow-y-auto rounded-lg border">
                                <button
                                    v-for="propietario in propietariosFiltrados"
                                    :key="propietario.id"
                                    type="button"
                                    class="block w-full border-b px-4 py-3 text-left last:border-0 hover:bg-gray-50"
                                    @click="seleccionarPropietario(propietario)"
                                >
                                    <span class="block font-medium">{{ propietario.nombre }} {{ propietario.apellido }}</span>
                                    <span class="text-sm text-muted-foreground">Documento: {{ propietario.documento }}</span>
                                </button>
                                <p v-if="propietariosFiltrados.length === 0" class="px-4 py-3 text-sm text-muted-foreground">No se encontró ningún propietario.</p>
                            </div>
                            <p v-else-if="props.propietarios.length === 0" class="text-sm text-red-600">No hay propietarios registrados.</p>
                        </template>
                        <p v-if="form.errors.id_propietario" class="text-sm text-red-600">{{ form.errors.id_propietario }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="placa">Placa</Label>
                            <Input id="placa" v-model="form.placa" required />
                            <p v-if="form.errors.placa" class="text-sm text-red-600">{{ form.errors.placa }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="marca">Marca</Label>
                            <Input id="marca" v-model="form.marca" required />
                            <p v-if="form.errors.marca" class="text-sm text-red-600">{{ form.errors.marca }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="linea">Línea</Label>
                            <Input id="linea" v-model="form.linea" required />
                            <p v-if="form.errors.linea" class="text-sm text-red-600">{{ form.errors.linea }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="modelo">Modelo</Label>
                            <Input id="modelo" v-model="form.modelo" required />
                            <p v-if="form.errors.modelo" class="text-sm text-red-600">{{ form.errors.modelo }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" :disabled="form.processing || !form.id_propietario" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                            Guardar cambios
                        </button>
                        <button type="button" class="rounded-md border px-4 py-2 hover:bg-gray-50" @click="router.get('/vehiculos')">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
