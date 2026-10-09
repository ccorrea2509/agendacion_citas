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

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="tipo_documento">Tipo de documento</Label>
                            <Input id="tipo_documento" v-model="form.tipo_documento" placeholder="CC, NIT, pasaporte..." required />
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
