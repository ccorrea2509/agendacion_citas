<script setup lang="ts">
interface PuestoTrabajo {
    id: number;
    numero_puesto: number;
}
interface Citas {
    id: number;
    cita_fecha: string;
    created_at: string;
}

defineProps<{
    fecha: string;
    puestosDisponibles: PuestoTrabajo[];
    citas: Citas[];
}>();
</script>

<template>
    <div class="rounded-lg border p-4">
        <h2 class="mb-4 text-lg font-semibold">
            Puestos disponibles para {{ fecha }}
        </h2>

        <div
            v-for="puesto in puestosDisponibles"
            :key="puesto.id"
            class="mb-2 rounded-lg border p-3"
        >
            Puesto {{ puesto.numero_puesto }}
        </div>

        <p
            v-if="puestosDisponibles.length <= 0"
            class="text-sm text-gray-500"
        >
            No hay puestos disponibles para esta fecha.
        </p>
    </div>
    <div class="rounded-lg border p-4">
        <h2 class="mb-4 text-lg font-semibold">
            Citas disponibles para {{ fecha }}
        </h2>

        <div
            v-for="cita in citas"
            :key="cita.id"
            class="mb-2 rounded-lg border p-3"
        >
            Citas {{ cita.cita_fecha }}- Creacion: {{ cita.created_at }}
        </div>

        <p
            v-if="puestosDisponibles.length <= 0"
            class="text-sm text-gray-500"
        >
            No hay citas disponibles para esta fecha.
        </p>
    </div>
</template>