<script setup lang="ts">
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    defaultValue?: string;
    modelValue?: string;
    class?: HTMLAttributes['class'];
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <textarea
        v-model="modelValue"
        :class="
            cn(
                'flex min-h-[100px] w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition-all resize-y',
                'placeholder:text-gray-400',
                'focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20',
                'hover:border-gray-400',
                'disabled:cursor-not-allowed disabled:bg-gray-100 disabled:opacity-50',
                props.class,
            )
        "
    />
</template>