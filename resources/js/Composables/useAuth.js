import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

export function useAuth() {
    const page = usePage();
    
    const user = computed(() => page.props.auth?.user || null);
    const isAuthenticated = computed(() => !!user.value);

    const logout = () => {
        router.post(route('logout'));
    };

    const redirectToDashboard = () => {
        if (!user.value) return;

        const roles = user.value.roles || [];
        
        if (roles.includes('admin')) {
            router.visit(route('admin.dashboard'));
        } else if (roles.includes('applicant')) {
            router.visit(route('applicant.dashboard'));
        } else if (roles.includes('sponsor')) {
            router.visit(route('sponsor.dashboard'));
        }
    };

    return {
        user,
        isAuthenticated,
        logout,
        redirectToDashboard
    };
}