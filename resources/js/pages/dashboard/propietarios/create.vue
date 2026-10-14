<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const form = useForm({
    tipo_documento: '',
    documento: '',
    nombre: '',
    apellido: '',
    telefono: '',
    email: '',
    direccion: '',
    placa: '',
    linea: '',
    marca: '',
    modelo: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Propietarios', href: '/propietarios' },
    { title: 'Registrar', href: '/propietarios/create' },
];

function submit() {
    form.post('/propietarios', { onSuccess: () => form.reset() });
}
</script>

<template>
    <Head title="Registrar propietario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <form class="p-6 md:p-8" @submit.prevent="submit">
                    <h2 class="mb-6 text-2xl font-semibold text-gray-800">Registrar propietario</h2>
                    <p class="mb-4 text-sm text-muted-foreground">Se creará el acceso del cliente automáticamente. Su contraseña inicial será su número de documento.</p>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="tipo_documento">Tipo de documento</Label>
                            <select
                                id="tipo_documento"
                                v-model="form.tipo_documento"
                                required
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            >
                                <option value="" disabled>Selecciona el tipo de documento</option>
                                <option value="CC">Cédula de ciudadanía (CC)</option>
                                <option value="CE">Cédula de extranjería (CE)</option>
                                <option value="TI">Tarjeta de identidad (TI)</option>
                                <option value="NIT">NIT</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                            <p v-if="form.errors.tipo_documento" class="text-sm text-red-600">{{ form.errors.tipo_documento }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="documento">Número de documento</Label>
                            <Input id="documento" v-model="form.documento" placeholder="Número de documento" required />
                            <p v-if="form.errors.documento" class="text-sm text-red-600">{{ form.errors.documento }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="nombre">Nombre</Label>
                            <Input id="nombre" v-model="form.nombre" placeholder="Nombre" required />
                            <p v-if="form.errors.nombre" class="text-sm text-red-600">{{ form.errors.nombre }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="apellido">Apellido</Label>
                            <Input id="apellido" v-model="form.apellido" placeholder="Apellido" required />
                            <p v-if="form.errors.apellido" class="text-sm text-red-600">{{ form.errors.apellido }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="telefono">Teléfono</Label>
                            <Input id="telefono" v-model="form.telefono" type="tel" placeholder="Teléfono" required />
                            <p v-if="form.errors.telefono" class="text-sm text-red-600">{{ form.errors.telefono }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" v-model="form.email" type="email" placeholder="Email" required />
                            <p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>
                        <div class="grid gap-2 md:col-span-2">
                            <Label for="direccion">Dirección</Label>
                            <Input id="direccion" v-model="form.direccion" placeholder="Dirección" required />
                            <p v-if="form.errors.direccion" class="text-sm text-red-600">{{ form.errors.direccion }}</p>
                        </div>

                        <h3 class="mt-4 text-lg font-semibold md:col-span-2">Datos del vehículo</h3>
                        <div class="grid gap-2">
                            <Label for="placa">Placa</Label>
                            <Input id="placa" v-model="form.placa" placeholder="Placa" required />
                            <p v-if="form.errors.placa" class="text-sm text-red-600">{{ form.errors.placa }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="linea">Línea</Label>
                            <Input id="linea" v-model="form.linea" placeholder="Línea" required />
                            <p v-if="form.errors.linea" class="text-sm text-red-600">{{ form.errors.linea }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="marca">Marca</Label>
                            <Input id="marca" v-model="form.marca" placeholder="Marca" required />
                            <p v-if="form.errors.marca" class="text-sm text-red-600">{{ form.errors.marca }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="modelo">Modelo</Label>
                            <Input id="modelo" v-model="form.modelo" placeholder="Modelo" required />
                            <p v-if="form.errors.modelo" class="text-sm text-red-600">{{ form.errors.modelo }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                            Guardar
                        </button>
                        <button type="button" class="rounded-md border px-4 py-2 hover:bg-gray-50" @click="router.get('/propietarios')">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
