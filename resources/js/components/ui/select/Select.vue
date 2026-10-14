<script setup lang="ts">
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import type { HTMLAttributes } from 'vue';

const props = withDefaults(
    defineProps<{
        defaultValue?: string | number;
        modelValue?: string | number;
        class?: HTMLAttributes['class'];
        placeholder?: string;
        disabled?: boolean;
        required?: boolean;
    }>(),
    {
        placeholder: 'Seleccione una opción',
        disabled: false,
        required: false,
    },
);

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <select
        v-model="modelValue"
        :disabled="props.disabled"
        :required="props.required"
        :class="
            cn(
                'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
                props.class,
            )
        "
    >
        <option value="" disabled>
            {{ props.placeholder }}
        </option>

        <slot />
    </select>
</template>


