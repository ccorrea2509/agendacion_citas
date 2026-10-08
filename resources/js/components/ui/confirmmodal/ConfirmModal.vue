<script setup lang="ts">    
defineProps<{ show: boolean; title?: string; message?: string; }>(); 
const emit = defineEmits<{ close: []; confirm: []; }>(); 
</script>
<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @click.self="emit('close')">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl"> <!-- Título -->
            <h2 class="text-xl font-semibold text-gray-800"> {{ title ?? 'Confirmar eliminación' }} </h2>
            <!-- Mensaje -->
            <p class="mt-3 text-sm text-gray-600"> {{ message ?? '¿Estás seguro de que deseas eliminar este registro?'
            }} </p> <!-- Botones -->
            <div class="mt-6 flex justify-end gap-3"> <button type="button" @click="emit('close')"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Cancelar </button> <button type="button" @click="emit('confirm')"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"> Eliminar
                </button> </div>
        </div>
    </div>
</template>