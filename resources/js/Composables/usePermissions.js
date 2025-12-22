import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const page = usePage();
    
    const user = computed(() => page.props.auth?.user || null);
    const permissions = computed(() => page.props.auth?.user?.permissions || []);
    const roles = computed(() => page.props.auth?.user?.roles || []);

    const hasPermission = (permission) => {
        return permissions.value.includes(permission);
    };

    const hasAnyPermission = (perms) => {
        return perms.some(permission => permissions.value.includes(permission));
    };

    const hasAllPermissions = (perms) => {
        return perms.every(permission => permissions.value.includes(permission));
    };

    const hasRole = (role) => {
        return roles.value.includes(role);
    };

    const hasAnyRole = (checkRoles) => {
        return checkRoles.some(role => roles.value.includes(role));
    };

    const isAdmin = computed(() => hasRole('admin'));
    const isApplicant = computed(() => hasRole('applicant'));
    const isSponsor = computed(() => hasRole('sponsor'));

    return {
        user,
        permissions,
        roles,
        hasPermission,
        hasAnyPermission,
        hasAllPermissions,
        hasRole,
        hasAnyRole,
        isAdmin,
        isApplicant,
        isSponsor
    };
}