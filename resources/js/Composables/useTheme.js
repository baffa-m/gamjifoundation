import { ref, watch } from 'vue';

const isDark = ref(false);

// Initialize from localStorage
if (typeof window !== 'undefined') {
  const stored = localStorage.getItem('theme');
  isDark.value = stored === 'dark';
}

// Watch and persist changes
watch(isDark, (newValue) => {
  if (typeof window !== 'undefined') {
    localStorage.setItem('theme', newValue ? 'dark' : 'light');
  }
});

export function useTheme() {
  const toggleTheme = () => {
    isDark.value = !isDark.value;
  };

  return {
    isDark,
    toggleTheme
  };
}