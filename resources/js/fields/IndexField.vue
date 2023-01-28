<template>
  <div :class="`text-${field.textAlign} nova-cloudinary`">
    <template v-if="hasValues">
      <div :class="{ dark }" class="isolate flex -space-x-2">
        <template
          v-for="(file, index) in thumbnails"
          :key="file.public_id"
        >
          <img
            v-if="file?.resource_type === 'image' && file?.format !== 'pdf'"
            :src="file.secure_url"
            class="relative inline-block h-10 w-10 rounded-full ring-white dark:ring-gray-500 ring-1 shadow object-cover"
            :class="`z-${index + 1}0`"
          />

          <img
            v-else-if="file?.resource_type === 'video'"
            :src="poster(file)"
            class="relative inline-block h-10 w-10 rounded-full ring-white dark:ring-gray-500 ring-1 shadow object-cover"
            :class="`z-${index + 1}0`"
          />

          <div
            v-else
            class="relative inline-block flex items-center justify-center h-10 w-10 rounded-full ring-white dark:ring-gray-500 ring-1 shadow bg-gray-50 dark:bg-gray-900"
            :class="`z-${index + 1}0`"
          >
            <DocumentIcon class="h-6 w-6"/>
          </div>

        </template>
        <div
          v-if="rest > 0"
          class="relative z-40 inline-block h-10 w-10 rounded-full ring-white dark:ring-gray-500 ring-1 bg-gray-100 flex items-center justify-center shadow"
        >
          +{{ rest }}
        </div>
      </div>
    </template>
    <template v-else>
      <p>&mdash;</p>
    </template>
  </div>
</template>

<script>
import { take } from 'lodash'
import {
  DocumentIcon,
} from '@heroicons/vue/24/outline'
import { pathinfo } from 'locutus/php/filesystem'
import SyncWithDarkMode from '@/mixins/SyncWithDarkMode'

export default {
  mixins: [SyncWithDarkMode],

  props: ['resourceName', 'field'],

  data: ({}),

  components: {
    DocumentIcon,
  },

  computed: {
    values() {
      return this.field.value || []
    },
    hasValues() {
      return this.values.length
    },
    thumbnails() {
      return take(this.values, 3)
    },
    rest() {
      return Math.max((this.values).length - 3, 0)
    },
  },

  methods: {
    poster(file) {
      const extension = pathinfo(file.secure_url, 4)

      if (extension.length) {
        return file.secure_url.replace(extension, 'jpg')
      }

      return file.secure_url
    }
  },
}
</script>
