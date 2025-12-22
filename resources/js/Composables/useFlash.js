import { computed, watch, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useFlash() {
    const page = usePage();
    const show = ref(false);
    
    const flash = computed(() => page.props.flash || {});
    const message = computed(() => flash.value.success || flash.value.error || flash.value.message);
    const type = computed(() => {
        if (flash.value.success) return 'success';
        if (flash.value.error) return 'error';
        return 'info';
    });

    watch(message, (newMessage) => {
        if (newMessage) {
            show.value = true;
            setTimeout(() => {
                show.value = false;
            }, 5000);
        }
    });

    const close = () => {
        show.value = false;
    };

    return {
        show,
        message,
        type,
        close
    };
}