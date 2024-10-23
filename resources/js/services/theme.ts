// src/theme.js
import { computed, reactive, ref } from 'vue';

const state = reactive({
    theme: 'light',
});

export function useTheme() {
    function setTheme(newTheme: string) {
        state.theme = newTheme;
        document.documentElement.className = ''; // Reset class list
        document.documentElement.classList.add(`${newTheme}-theme`);
        localStorage.setItem('theme', newTheme);
    }

    function toggleTheme() {
        setTheme(state.theme === 'light' ? 'dark' : 'light');
    }

    function initializeTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            setTheme(prefersDark ? 'dark' : 'light');
        }
    }

    return {
        theme: computed(() => state.theme),
        toggleTheme,
        initializeTheme,
    };
}
