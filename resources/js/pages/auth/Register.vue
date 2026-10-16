<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

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
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crear cuenta de cliente" description="Registra tus datos y los de tu vehículo">
        <Head title="Registro de cliente" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <h2 class="text-lg font-semibold">Datos del propietario</h2>

                <div class="grid gap-2">
                    <Label for="tipo_documento">Tipo de documento</Label>
                    <select
                        id="tipo_documento"
                        v-model="form.tipo_documento"
                        required
                        autofocus
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    >
                        <option value="" disabled>Selecciona el tipo de documento</option>
                        <option value="CC">Cédula de ciudadanía (CC)</option>
                        <option value="CE">Cédula de extranjería (CE)</option>
                        <option value="TI">Tarjeta de identidad (TI)</option>
                        <option value="NIT">NIT</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                    <InputError :message="form.errors.tipo_documento" />
                </div>

                <div class="grid gap-2">
                    <Label for="documento">Número de documento</Label>
                    <Input id="documento" v-model="form.documento" required autocomplete="off" placeholder="Número de documento" />
                    <InputError :message="form.errors.documento" />
                </div>

                <div class="grid gap-2">
                    <Label for="nombre">Nombre</Label>
                    <Input id="nombre" v-model="form.nombre" required autocomplete="given-name" placeholder="Nombre" />
                    <InputError :message="form.errors.nombre" />
                </div>

                <div class="grid gap-2">
                    <Label for="apellido">Apellido</Label>
                    <Input id="apellido" v-model="form.apellido" required autocomplete="family-name" placeholder="Apellido" />
                    <InputError :message="form.errors.apellido" />
                </div>

                <div class="grid gap-2">
                    <Label for="telefono">Teléfono</Label>
                    <Input id="telefono" v-model="form.telefono" type="tel" required autocomplete="tel" placeholder="Teléfono" />
                    <InputError :message="form.errors.telefono" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input id="email" v-model="form.email" type="email" required autocomplete="email" placeholder="correo@ejemplo.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="direccion">Dirección</Label>
                    <Input id="direccion" v-model="form.direccion" required autocomplete="street-address" placeholder="Dirección" />
                    <InputError :message="form.errors.direccion" />
                </div>

                <h2 class="mt-2 text-lg font-semibold">Datos del vehículo</h2>

                <div class="grid gap-2">
                    <Label for="placa">Placa</Label>
                    <Input id="placa" v-model="form.placa" required autocomplete="off" placeholder="Placa" />
                    <InputError :message="form.errors.placa" />
                </div>

                <div class="grid gap-2">
                    <Label for="linea">Línea</Label>
                    <Input id="linea" v-model="form.linea" required placeholder="Línea del vehículo" />
                    <InputError :message="form.errors.linea" />
                </div>

                <div class="grid gap-2">
                    <Label for="marca">Marca</Label>
                    <Input id="marca" v-model="form.marca" required placeholder="Marca" />
                    <InputError :message="form.errors.marca" />
                </div>

                <div class="grid gap-2">
                    <Label for="modelo">Modelo</Label>
                    <Input id="modelo" v-model="form.modelo" required placeholder="Modelo" />
                    <InputError :message="form.errors.modelo" />
                </div>

                <h2 class="mt-2 text-lg font-semibold">Acceso a la cuenta</h2>

                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <Input id="password" v-model="form.password" type="password" required autocomplete="new-password" placeholder="Contraseña" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmar contraseña</Label>
                    <Input id="password_confirmation" v-model="form.password_confirmation" type="password" required autocomplete="new-password" placeholder="Repite la contraseña" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                ¿Ya tienes cuenta?
                <TextLink :href="route('login')" class="underline underline-offset-4">Inicia sesión</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
