<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, UserCog, Wrench, CalendarDays, CarFront, UserRound, Coins, BetweenHorizontalStart  } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Inicio',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Personal',
        href: '/Personal',
        icon: UserCog ,
    },
    {
        title: 'Servicios',
        href: '/dashboard',
        icon: Wrench,
    },
    {
        title: 'Citas',
        href: '/dashboard',
        icon: CalendarDays,
    },
    {
        title: 'Puestos Trabajo',
        href: '/PuestoTrabajo',
        icon: BetweenHorizontalStart,
    },
    {
        title: 'Vehiculos',
        href: '/dashboard',
        icon: CarFront,
    },
    {
        title: 'Propietarios',
        href: '/propietarios',
        icon: UserRound,
    },
    {
        title: 'Ingresos',
        href: '/dashboard',
        icon: Coins,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/ccorrea2509/agendacion_citas.git',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://trello.com/invite/b/6abaefab5abcf97716206e17/ATTI34055585952ab251984d061a47419124F9E878D8/laboratorio-de-software',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
