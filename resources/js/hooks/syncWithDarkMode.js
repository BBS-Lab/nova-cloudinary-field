import { ref } from 'vue'

export function syncWithDarkMode() {
  const dark = ref(false)

  dark.value = document.documentElement.classList.contains('dark')

  const eventCallback = (theme) => dark.value = theme === 'dark'

  const unregisterEvent = () => {
    window.Nova.$off('nova-theme-switched', eventCallback)
  }

  return {
    dark,
    unregisterEvent,
  }
}
