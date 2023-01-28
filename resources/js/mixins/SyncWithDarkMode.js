export default {
  data: ({
    dark: null,
  }),

  mounted() {
    if (this.dark === undefined || this.dark === null) {
      this.dark = document.documentElement.classList.contains('dark')
    }

    window.Nova.$on('nova-theme-switched', this.darkModeCallback)
  },

  beforeUnmount() {
    window.Nova.$off('nova-theme-switched', this.darkModeCallback)
  },

  methods: {
    darkModeCallback({ theme }) {
      this.dark = theme === 'dark'
    },
  }
}
