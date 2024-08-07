<template>
  <TransitionRoot :show="isOpen" as="template" class="nova-file-manager">
    <Dialog as="div" class="relative z-[60]" style="z-index: 999" @close="closeModal">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-800/20 backdrop-blur-sm transition-opacity" />
      </TransitionChild>
      <div :class="{ dark }" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end sm:items-center justify-center min-h-full p-0 md:p-4">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <slot :close="closeModal" :is-open="isOpen" :dark="dark"></slot>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { Dialog, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { onBeforeUnmount, onMounted } from 'vue'
import { syncWithDarkMode } from '@/hooks'

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  isOpen: {
    type: Boolean,
    required: true,
  },
  closeModal: {
    type: Function,
    required: true,
  }
})

const { dark, unregisterEvent } = syncWithDarkMode()

onBeforeUnmount(() => {
  unregisterEvent()

  if (props.isOpen) {
    closeModal()
  }

  setTimeout(() => {
    // temporary fix
    // @see https://github.com/tailwindlabs/headlessui/issues/1319
    document.documentElement.style.removeProperty('overflow')
    document.documentElement.style.removeProperty('padding-right')
  }, 100)
})


const closeModal = () => {
  props.closeModal()
}
</script>
