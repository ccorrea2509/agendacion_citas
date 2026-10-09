<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 ">
        <div class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('/imagenes/fondo3.webp')"/>
            <Link class="relative z-20 flex items-center text-lg font-medium">
                <img src="/imagenes/nombre.png" alt="Background" class="w-1/2"/>
            </Link>
            <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <footer class="text-sm text-neutral-300">SERVI+ TU TALLER, MAS SIMPLE</footer>
                </blockquote>
            </div>
        </div>
        
        <div class="lg:p-8 ">
            
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <div class="flex flex-col text-center">
                    <img src="/imagenes/logo1.png" alt="Background" class="w-1/2 mx-auto"/>
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">Login</h1>
                    <p class="text-sm text-muted-foreground" v-if="description">Ingrese usuario y contraseña</p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
