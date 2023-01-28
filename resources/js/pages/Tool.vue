<script setup>
import { syncWithDarkMode } from '@/hooks'
import { onMounted, onUnmounted, ref } from 'vue'

const { dark, unregisterEvent } = syncWithDarkMode()

const container = ref(null)

const props = defineProps({
  configuration: {
    type: Object,
    required: true
  }
})

onMounted(() => {
  cloudinary.openMediaLibrary({
    ...props.configuration,
    inline_container: container.value,
    remove_header: true,
    z_index: 30,
  })
})

onUnmounted(() => {
  unregisterEvent()
})
</script>

<template>
  <div class="nova-cloudinary">
    <div :class="{ dark }">
      <Head :title="__('NovaCloudinary.title')">
        <title>{{ __('NovaCloudinary.title') }}</title>
      </Head>

      <div class="h-[80vh] w-full card border rounded-lg z-40 overflow-hidden" ref="container">
      </div>
    </div>
  </div>
</template>
