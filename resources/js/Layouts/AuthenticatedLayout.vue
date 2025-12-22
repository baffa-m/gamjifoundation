<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    Menu, X, Home, Award, Users, FileText, 
    Settings, LogOut, Bell, Search, ChevronDown, 
    ChevronLeft, ChevronRight, LayoutDashboard,
    GraduationCap, Building2, Newspaper, Sun, Moon
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import { usePermissions } from '@/Composables/usePermissions';
import FlashMessage from '@/Components/Shared/FlashMessage.vue';

const page = usePage();
const { user, isAdmin, isApplicant, isSponsor } = usePermissions();
const { isDark, toggleTheme } = useTheme();

const sidebarOpen = ref(false); // Mobile toggle
const isSidebarCollapsed = ref(false); // Desktop collapse

const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const navigation = computed(() => {
    if (isAdmin.value) {
        return [
            { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard },
            { name: 'Applications', href: route('admin.applications.index'), icon: FileText },
            { name: 'Awards', href: route('admin.awards.index'), icon: Award },
            { name: 'Applicants', href: route('admin.applicants.index'), icon: GraduationCap },
            { name: 'Sponsors', href: route('admin.sponsors.index'), icon: Building2 },
            { name: 'Hero Slides', href: route('admin.hero-slides.index'), icon: Newspaper },
            { name: 'News', href: route('admin.news.index'), icon: FileText },
        ];
    } else if (isApplicant.value) {
        return [
            { name: 'Dashboard', href: route('applicant.dashboard'), icon: Home },
            { name: 'Browse Awards', href: route('applicant.awards.index'), icon: Award },
            { name: 'My Applications', href: route('applicant.applications.index'), icon: FileText },
        ];
    } else if (isSponsor.value) {
        return [
            { name: 'Dashboard', href: route('sponsor.dashboard'), icon: Home },
            { name: 'My Awards', href: route('sponsor.awards.index'), icon: Award },
        ];
    }
    return [];
});

const isActive = (href) => {
    return route().current(href.replace(route().t.url + '/', ''));
};
</script>

<template>
    <div :class="['min-h-screen font-sans flex transition-colors duration-500', isDark ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-800']">
        <FlashMessage />

        <!-- Mobile sidebar backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden transition-opacity"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 text-white transition-all duration-300 ease-in-out flex flex-col shadow-premium-lg lg:static border-r border-slate-800/50',
                sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0',
                isSidebarCollapsed ? 'lg:w-20' : 'lg:w-64'
            ]"
        >
            <!-- Ambient Background Pattern -->
            <div class="absolute inset-0 opacity-5 pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-brand-500/10 to-transparent"></div>
                <div class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-brand-500/5 to-transparent"></div>
            </div>

            <!-- Logo -->
            <div class="relative h-16 flex items-center px-6 border-b border-slate-800/50 shrink-0 backdrop-blur-sm">
                <Link href="/" class="flex items-center gap-3 overflow-hidden group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center shrink-0 shadow-glow group-hover:shadow-glow-lg transition-all duration-300 group-hover:scale-110">
                        <span class="font-bold text-white font-display text-lg">G</span>
                    </div>
                    <span 
                        :class="['font-bold text-xl tracking-wide font-display transition-all duration-300 bg-gradient-to-r from-white to-brand-100 bg-clip-text text-transparent', isSidebarCollapsed ? 'lg:opacity-0 lg:hidden' : 'opacity-100']"
                    >
                        GAMJI
                    </span>
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="relative flex-1 overflow-y-auto py-6 space-y-1 px-3 no-scrollbar">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'relative flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 group overflow-hidden',
                        isActive(item.href)
                            ? 'text-white bg-gradient-to-r from-brand-600/20 to-brand-500/10 shadow-glow-sm'
                            : 'text-slate-400 hover:text-white hover:bg-white/5 hover:shadow-inner-glow'
                    ]"
                    :title="isSidebarCollapsed ? item.name : ''"
                >
                    <!-- Active Glow -->
                    <div
                        v-if="isActive(item.href)"
                        class="absolute inset-0 bg-gradient-to-r from-brand-500/20 to-transparent opacity-50"
                    />

                    <!-- Active Indicator -->
                    <div
                        v-if="isActive(item.href)"
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-gradient-to-b from-brand-400 to-brand-600 rounded-r shadow-glow"
                    />

                    <component 
                        :is="item.icon" 
                        :class="[
                            'relative z-10 w-5 h-5 transition-all duration-300',
                            isActive(item.href) ? 'text-brand-400 drop-shadow-lg' : 'text-slate-400 group-hover:text-brand-400 group-hover:scale-110',
                            isSidebarCollapsed ? 'lg:mx-auto' : ''
                        ]" 
                    />
                    <span :class="['relative z-10 font-medium transition-all duration-300', isSidebarCollapsed ? 'lg:opacity-0 lg:hidden' : 'opacity-100']">
                        {{ item.name }}
                    </span>

                    <!-- Tooltip for collapsed state -->
                    <div 
                        v-if="isSidebarCollapsed"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                    >
                        {{ item.name }}
                    </div>
                </Link>
            </nav>

            <!-- User Profile (Bottom) -->
            <div class="p-4 border-t border-slate-800 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white font-bold shrink-0 border border-slate-600">
                        {{ user?.name?.charAt(0) }}
                    </div>
                    <div 
                        :class="['transition-all duration-300 overflow-hidden', isSidebarCollapsed ? 'lg:opacity-0 lg:w-0' : 'opacity-100 w-auto']"
                    >
                        <p class="text-sm font-semibold truncate text-slate-200">{{ user?.name }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button" 
                                class="text-xs text-slate-500 hover:text-brand-400 transition-colors truncate flex items-center gap-1"
                            >
                                <LogOut class="w-3 h-3" /> Sign out
                            </Link>
                            <button 
                                @click="toggleTheme" 
                                class="lg:hidden text-slate-500 hover:text-brand-400 transition-colors"
                            >
                                <Sun v-if="isDark" class="w-3 h-3" />
                                <Moon v-else class="w-3 h-3" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300">
            <!-- Top Header -->
            <header :class="['h-16 backdrop-blur-md border-b flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm transition-colors duration-500', isDark ? 'bg-slate-900/80 border-slate-800' : 'bg-white/80 border-slate-200']">
                <!-- Left: Toggle & Breadcrumb -->
                <div class="flex items-center gap-4">
                    <button 
                        @click="sidebarOpen = !sidebarOpen" 
                        class="lg:hidden text-slate-500 hover:text-brand-600 transition"
                    >
                        <Menu v-if="!sidebarOpen" class="w-6 h-6" />
                        <X v-else class="w-6 h-6" />
                    </button>

                    <button 
                        @click="toggleSidebar" 
                        :class="['hidden lg:flex items-center justify-center w-8 h-8 rounded-lg transition', isDark ? 'hover:bg-slate-800 text-slate-400 hover:text-brand-400' : 'hover:bg-slate-100 text-slate-500 hover:text-brand-600']"
                    >
                        <ChevronLeft v-if="!isSidebarCollapsed" class="w-5 h-5" />
                        <ChevronRight v-else class="w-5 h-5" />
                    </button>

                    <div :class="['hidden md:flex items-center text-sm font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">
                        <span class="hover:text-brand-600 cursor-pointer transition-colors">Home</span>
                        <span class="mx-2">/</span>
                        <span :class="isDark ? 'text-slate-200' : 'text-slate-800'">Dashboard</span>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-4">
                        <!-- Theme Toggle -->
                        <button 
                            @click="toggleTheme" 
                            :class="['p-2 rounded-full transition-colors', isDark ? 'bg-slate-800 text-brand-400 hover:bg-slate-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
                        >
                            <Sun v-if="isDark" class="w-5 h-5" />
                            <Moon v-else class="w-5 h-5" />
                        </button>

                        <!-- Search -->
                        <div class="relative group">
                            <input 
                                type="text" 
                                placeholder="Search data..." 
                                :class="['pl-10 pr-4 py-2 rounded-full border focus:outline-none focus:ring-1 focus:ring-brand-500 w-64 transition-all text-sm', isDark ? 'bg-slate-800 border-slate-700 text-slate-200 placeholder-slate-500' : 'bg-slate-50 border-slate-200 text-slate-800 placeholder-slate-400']" 
                            />
                            <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-brand-500 transition-colors" />
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <button :class="['relative p-2 rounded-full transition', isDark ? 'hover:bg-slate-800 text-slate-400 hover:text-brand-400' : 'hover:bg-slate-100 text-slate-500 hover:text-brand-600']">
                        <Bell class="w-6 h-6" />
                        <span :class="['absolute top-1.5 right-2 w-2 h-2 bg-red-500 rounded-full border animate-pulse', isDark ? 'border-slate-900' : 'border-white']"></span>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 scroll-smooth">
                <div class="max-w-7xl mx-auto animate-fade-in-up">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>