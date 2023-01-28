<template>
  <BaseModal as="template" class="nova-cloudinary" name="preview" :close-modal="closeModal" :is-open="isOpen">
    <DialogPanel
      class="relative bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-7xl p-4 flex flex-col gap-4"
    >
      <div
        class="w-full flex flex-col flex-col-reverse gap-y-2 md:flex-row justify-between items-start"
      >
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-400 break-all w-full">
          {{ file?.public_id }}
        </h2>

        <div class="flex flex-row gap-2 justify-end flex-shrink-0">
          <IconButton
            @click="copy(file)"
            variant="secondary"
            :title="__('NovaCloudinary.actions.copy')"
          >
            <ClipboardDocumentIcon class="w-5 h-5" />
          </IconButton>

          <IconButton
            :as-anchor="true"
            :download="file?.public_id"
            :href="file?.secure_url"
            target="_blank"
            variant="secondary"
            :title="__('NovaCloudinary.actions.download')"
          >
            <CloudArrowDownIcon class="w-5 h-5" />
          </IconButton>

          <IconButton
            ref="buttonRef"
            @click="closeModal"
            :title="__('NovaCloudinary.actions.close')"
          >
            <XMarkIcon class="w-5 h-5" />
          </IconButton>
        </div>
      </div>

      <div class="overflow-hidden flex flex-col md:flex-row gap-4 w-full">
        <div
          class="block relative w-full md:w-4/6 overflow-hidden rounded-lg bg-gray-500/10 flex items-center justify-center"
        >
          <img
            v-if="file?.resource_type === 'image' && file?.format !== 'pdf'"
            :src="file.secure_url"
            class="relative max-h-[80vh]"
          />

          <video
            v-else-if="file?.resource_type === 'video'"
            class="w-full max-w-screen max-h-[80vh] relative"
            controls="controls"
          >
            <source :src="file?.secure_url" />
            Sorry, your browser doesn't support embedded videos.
          </video>

          <embed
            v-else-if="file?.format === 'pdf'"
            :src="file?.secure_url"
            type="application/pdf"
            class="w-full max-w-screen h-[80vh]"
          />

          <DocumentIcon v-else class="h-40 w-40 text-gray-500 m-12" />
        </div>

        <div class="w-full md:w-2/6">
          <div>
            <h3 class="font-medium text-gray-800 dark:text-gray-100">
              {{ __('NovaCloudinary.preview.information') }}
            </h3>
            <dl
              class="mt-2 divide-y divide-gray-200 dark:divide-gray-800/40 border-t border-b border-gray-300 dark:border-gray-800/70"
            >
              <div class="flex justify-between py-3 text-sm font-medium">
                <dt class="text-gray-500">
                  {{ __('NovaCloudinary.meta.size') }}
                </dt>
                <dd class="text-gray-400 dark:text-gray-600">
                  {{ filesize(file?.bytes) }}
                </dd>
              </div>

              <div class="flex justify-between py-3 text-sm font-medium">
                <dt class="text-gray-500">
                  {{ __('NovaCloudinary.meta.resourceType') }}
                </dt>
                <dd class="text-gray-400 dark:text-gray-600">
                  {{ file?.resource_type }}
                </dd>
              </div>

              <div class="flex justify-between py-3 text-sm font-medium">
                <dt class="text-gray-500">
                  {{ __('NovaCloudinary.meta.format') }}
                </dt>
                <dd class="text-gray-400 dark:text-gray-600">
                  {{ file?.format }}
                </dd>
              </div>

              <div class="flex justify-between py-3 text-sm font-medium">
                <dt class="text-gray-500">
                  {{ __('NovaCloudinary.meta.createdAt') }}
                </dt>
                <dd class="text-gray-400 dark:text-gray-600">
                  {{ file?.created_at }}
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </DialogPanel>
  </BaseModal>
</template>

<script setup>
import { ref } from 'vue'
import { DialogPanel } from '@headlessui/vue'
import {
  ClipboardDocumentIcon,
  CloudArrowDownIcon,
  DocumentIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import IconButton from '@/components/IconButton'
import BaseModal from '@/components/BaseModal'
import Asset from '@/types/Asset'
import { useClipboard } from '@/hooks'
import { filesize } from 'filesize'

defineProps({
  file: {
    type: Asset,
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

const { copyToClipboard } = useClipboard()

// STATE
const buttonRef = ref(null)

const copy = file => {
  copyToClipboard(file.url)

  Nova.success('Copied !')
}
</script>
