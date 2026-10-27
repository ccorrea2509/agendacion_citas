<script setup lang="ts">
import { ref, computed, watch } from 'vue';

type Option = Record<string, string | number>;

const props = withDefaults(defineProps<{
    modelValue?: string | number | null;
    options: Option[];
    optionValue?: string;
    optionLabel?: string;
    placeholder?: string;
}>(), {
    optionValue: 'id',
    optionLabel: 'placa',
    placeholder: 'Buscar...',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number | null): void;
}>();

const search = ref('');
const showOptions = ref(false);

const selectedOption = computed(() =>
    props.options.find(
        option => option[props.optionValue] === props.modelValue
    )
);

const filteredOptions = computed(() => {
    const term = search.value.toLowerCase().trim();

    return props.options.filter(option =>
        String(option[props.optionLabel] ?? '')
            .toLowerCase()
            .includes(term)
    );
});

function selectOption(option: Option) {
    emit('update:modelValue', option[props.optionValue] as string | number);
    search.value = String(option[props.optionLabel] ?? '');
    showOptions.value = false;
}

watch(
    () => props.modelValue,
    () => {
        search.value = String(selectedOption.value?.[props.optionLabel] ?? '');
    },
    { immediate: true }
);
</script>

<template>
    <div class="relative w-full">
        <input
            v-model="search"
            type="text"
            :placeholder="placeholder"
            class="w-full rounded-md border border-gray-300 bg-background p-2.5"
            @focus="showOptions = true"
            @input="showOptions = true"
        />

        <div
            v-if="showOptions"
            class="absolute z-50 mt-1 max-h-52 w-full overflow-y-auto rounded-md border border-gray-200 bg-white shadow-lg"
        >
            <button
                v-for="option in filteredOptions"
                :key="option[optionValue]"
                type="button"
                class="block w-full px-3 py-2 text-left text-gray-800 hover:bg-gray-100"
                @click="selectOption(option)"
            >
                {{ option[optionLabel] }}
            </button>

            <p
                v-if="filteredOptions.length === 0"
                class="p-3 text-sm text-gray-500"
            >
                No se encontraron resultados.
            </p>
        </div>
    </div>
</template>
