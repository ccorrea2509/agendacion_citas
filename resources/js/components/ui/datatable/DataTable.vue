
<script setup lang="ts">
import { computed, ref } from 'vue';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
}

const props = defineProps<{
    data: Record<string, any>[];
    columns: Column[];
    perPage?: number;
}>();

const search = ref('');
const currentPage = ref(1);
const sortKey = ref('');
const sortDirection = ref<'asc' | 'desc'>('asc');

const itemsPerPage = computed(() => props.perPage ?? 10);

// Buscar
const filteredData = computed(() => {
    if (!search.value) {
        return props.data;
    }

    const searchTerm = search.value.toLowerCase();

    return props.data.filter((item) => {
        return props.columns.some((column) => {
            const value = item[column.key];

            return String(value ?? '')
                .toLowerCase()
                .includes(searchTerm);
        });
    });
});

// Ordenar
const sortedData = computed(() => {
    if (!sortKey.value) {
        return filteredData.value;
    }

    return [...filteredData.value].sort((a, b) => {
        const valueA = a[sortKey.value];
        const valueB = b[sortKey.value];

        if (valueA === valueB) {
            return 0;
        }

        if (sortDirection.value === 'asc') {
            return valueA > valueB ? 1 : -1;
        }

        return valueA < valueB ? 1 : -1;
    });
});

// Total de páginas
const totalPages = computed(() => {
    return Math.ceil(sortedData.value.length / itemsPerPage.value);
});

// Datos de la página actual
const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;

    return sortedData.value.slice(start, end);
});

// Ordenar columna
function sortBy(column: Column) {
    if (column.sortable === false) {
        return;
    }

    if (sortKey.value === column.key) {
        sortDirection.value =
            sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = column.key;
        sortDirection.value = 'asc';
    }
}

// Cambiar página
function changePage(page: number) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
}

// Cuando se busca, volver a la página 1
function handleSearch() {
    currentPage.value = 1;
}
</script>

<template>
    <div class="w-full space-y-4">

        <!-- Buscador -->
        <div class="flex items-center justify-between">
            <div class="relative w-full max-w-sm">
                <input
                    v-model="search"
                    @input="handleSearch"
                    type="text"
                    placeholder="Buscar..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                />
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm text-gray-600">

                <!-- Header -->
                <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                    <tr>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            scope="col"
                            class="px-6 py-3"
                        >
                            <button
                                v-if="column.sortable !== false"
                                type="button"
                                @click="sortBy(column)"
                                class="flex items-center gap-2 font-semibold hover:text-blue-600"
                            >
                                {{ column.label }}

                                <span v-if="sortKey === column.key">
                                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                            </button>

                            <span v-else>
                                {{ column.label }}
                            </span>
                        </th>
                        <th
                            v-if="$slots.actions"
                            scope="col"
                            class="px-6 py-3"
                        >
                            Acciones
                        </th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody>
                    <tr
                        v-for="item in paginatedData"
                        :key="item.id"
                        class="border-t hover:bg-gray-50"
                    >
                        <td
                            v-for="column in columns"
                            :key="column.key"
                            class="whitespace-nowrap px-6 py-4"
                        >
                            {{ item[column.key] }}
                        </td>
                        <td
                            v-if="$slots.actions"
                            class="px-6 py-4"
                        >
                            <slot name="actions" :item="item" />
                        </td>
                    </tr>

                    <!-- Sin resultados -->
                    <tr v-if="paginatedData.length === 0">
                        <td
                            :colspan="columns.length"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No se encontraron resultados.
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div
            v-if="totalPages > 1"
            class="flex items-center justify-between"
        >
            <span class="text-sm text-gray-600">
                Página {{ currentPage }} de {{ totalPages }}
            </span>

            <div class="flex gap-2">
                <button
                    type="button"
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="rounded-lg border px-3 py-2 text-sm hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Anterior
                </button>

                <button
                    v-for="page in totalPages"
                    :key="page"
                    type="button"
                    @click="changePage(page)"
                    :class="[
                        'rounded-lg border px-3 py-2 text-sm',
                        currentPage === page
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-gray-100'
                    ]"
                >
                    {{ page }}
                </button>

                <button
                    type="button"
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="rounded-lg border px-3 py-2 text-sm hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</template>

