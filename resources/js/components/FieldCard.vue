<template>
  <button
    type="button"
    class="relative cursor-pointer focus:rounded-md group focus:outline-none flex flex-col items-start cursor-grab"
    :title="name"
  >
    <div
      :class="[
        'relative block aspect-square w-full overflow-hidden rounded-lg hover:shadow-md hover:opacity-75 border border-gray-200/50 dark:border-gray-700/50 text-left',
        'group-focus-visible:outline group-focus-visible:outline-2 group-focus-visible:outline-blue-500/50',
      ]"
    >
      <div class="m-auto z-20 flex h-full items-center justify-center select-none">
        <div
          class="m-auto flex h-full w-full items-center justify-center bg-gray-50 dark:bg-gray-900 text-gray-600"
          v-if="isFile || isPdf"
        >
          <DocumentIcon class="w-16 h-16" />
        </div>

        <img
          v-if="isImage && !isPdf"
          :src="file.secure_url"
          draggable="false"
        />

        <template v-if="isVideo">
          <video class="pointer-events-none w-full h-full object-cover">
            <source :src="file.secure_url" />
            Sorry, your browser doesn't support embedded videos.
          </video>

          <div
            class="absolute m-auto flex items-center justify-center bg-transparent"
          >
            <PlayIcon class="h-16 w-16 text-white/60" />
          </div>
        </template>
      </div>

      <div class="absolute right-1 top-1" v-if="onDeselect">
        <button type="button" v-if="onDeselect" @click="onDeselect(file)" class="text-red-500">
          <XCircleIcon class="h-4 w-4" />
        </button>
      </div>
    </div>

    <p
      class="pointer-events-none mt-2 block truncate font-medium text-gray-900 dark:text-gray-50 text-left w-full text-xs"
      :title="file.public_id"
    >
      {{ file.public_id }}
    </p>

    <div
      class="gap-x-0.5 inline-flex flex-wrap items-center text-xs pointer-events-none block font-medium text-gray-500 text-left break-all"
    >
      <span>{{ filesize(file.bytes) }}</span>
      <span class="ml-0.5">&centerdot; {{ file.resource_type }}</span>
      <span class="ml-0.5">&centerdot; {{ file.format }}</span>
    </div>
  </button>
</template>

<script setup>
import { computed } from 'vue'
import { DocumentIcon } from '@heroicons/vue/24/outline'
import {
  PlayIcon,
  XCircleIcon,
} from '@heroicons/vue/24/solid'
import Asset from '@/types/Asset'
import { filesize } from 'filesize'

const props = defineProps({
  file: {
    type: Asset,
    default: null,
  },
  onDeselect: {
    type: Function,
  },
})

const isImage = computed(() => props.file.resource_type === 'image')
const isVideo = computed(() => props.file.resource_type === 'video')
const isPdf = computed(() => props.file.format === 'pdf')
const isFile = computed(() => props.file.resource_type !== 'image' && props.file.resource_type !== 'video')
const name = computed(() => props.file.public_id)
</script>
