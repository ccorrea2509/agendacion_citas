<script setup lang="ts">
import { ref } from 'vue';

interface Citas {
    id: number;
    cita_fecha: string;
    tiempo_aproximado: number;
    puesto_asignado: number;
    estado: string;
    created_at: string;
}

interface PuestoTrabajo {
    id: number;
    numero_puesto: string;
}

interface HorarioDisponible {
    inicio: string;
    fin: string;
}

interface PuestoConHorarios {
    puesto: PuestoTrabajo;
    horarios_disponibles: HorarioDisponible[];
}

const props = defineProps<{
    fecha: string;
    citas: Citas[];
    horarios_disponibles: PuestoConHorarios[];
}>();

const emit = defineEmits<{
    seleccionar: [puestoId: number, hora: string];
}>();

// Intervalo entre horarios disponibles
const intervaloMinutos = ref(30);

// Horario seleccionado
const horarioSeleccionado = ref<string | null>(null);

// Convertir HH:mm a minutos
const convertirAMinutos = (hora: string): number => {
    const [horas, minutos] = hora.split(':').map(Number);
    return horas * 60 + minutos;
};

// Convertir minutos a HH:mm
const convertirAHora = (minutos: number): string => {
    const horas = Math.floor(minutos / 60);
    const minutosRestantes = minutos % 60;

    return `${String(horas).padStart(2, '0')}:${String(minutosRestantes).padStart(2, '0')}`;
};

// Generar horarios según el intervalo
const generarHorarios = (inicio: string, fin: string): string[] => {
    const horarios: string[] = [];

    const inicioMinutos = convertirAMinutos(inicio);
    const finMinutos = convertirAMinutos(fin);

    for (
        let tiempo = inicioMinutos;
        tiempo < finMinutos;
        tiempo += intervaloMinutos.value
    ) {
        horarios.push(convertirAHora(tiempo));
    }

    return horarios;
};

// Seleccionar puesto y hora
const seleccionarHorario = (puestoId: number, hora: string) => {
    horarioSeleccionado.value = `${puestoId}-${hora}`;

    emit('seleccionar', puestoId, hora);
};
console.log(props.horarios_disponibles);
</script>

<template>
    <div class="space-y-6">

        <!-- Fecha seleccionada -->
        <div>
            <h1 class="text-3xl font-bold">
                {{ fecha }}
            </h1>

            <p class="mt-1 text-sm text-muted-foreground">
                Selecciona un puesto de trabajo y una hora disponible.
            </p>
        </div>

        <!-- Intervalo de horarios -->
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium">
                Intervalo:
            </label>

            <select
                v-model.number="intervaloMinutos"
                class="rounded-md border px-3 py-2 text-sm"
            >
                <option :value="20">20 minutos</option>
                <option :value="30">30 minutos</option>
            </select>
        </div>

        <!-- Puestos de trabajo -->
        <div
            v-for="item in horarios_disponibles"
            :key="item.puesto.id"
            class="rounded-lg border p-4 space-y-4"
        >

            <h2 class="text-lg font-semibold">
                Puesto de trabajo {{ item.puesto.numero_puesto }}
            </h2>

            <!-- Intervalos disponibles -->
            <div
                v-for="(intervalo, index) in item.horarios_disponibles"
                :key="index"
                class="space-y-3"
            >

                <p class="text-sm text-muted-foreground">
                    Disponible:
                    <span class="font-medium text-foreground">
                        {{ intervalo.inicio }} - {{ intervalo.fin }}
                    </span>
                </p>

                <!-- Horarios generados -->
                <div class="flex flex-wrap gap-3">

                    <label
                        v-for="hora in generarHorarios(
                            intervalo.inicio,
                            intervalo.fin
                        )"
                        :key="hora"
                        class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 transition-colors hover:bg-accent"
                        :class="{
                            'border-primary bg-accent':
                                horarioSeleccionado === `${item.puesto.id}-${hora}`
                        }"
                    >

                        <input
                            type="radio"
                            name="horario_cita"
                            :value="`${item.puesto.id}-${hora}`"
                            :checked="
                                horarioSeleccionado === `${item.puesto.id}-${hora}`
                            "
                            @change="
                                seleccionarHorario(item.puesto.id, hora)
                            "
                        />

                        <span class="text-sm font-medium">
                            {{ hora }}
                        </span>

                    </label>

                </div>

            </div>

            <!-- Sin horarios -->
            <p
                v-if="!item.horarios_disponibles?.length"
                class="text-sm text-muted-foreground"
            >
                Este puesto no tiene horarios disponibles.
            </p>

        </div>

        <!-- Sin puestos -->
        <p
            v-if="!horarios_disponibles?.length"
            class="text-sm text-muted-foreground"
        >
            No hay puestos disponibles para esta fecha.
        </p>

    </div>
</template>