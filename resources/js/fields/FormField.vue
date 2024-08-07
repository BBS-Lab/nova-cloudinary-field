<template>
  <DefaultField
    :errors="errors"
    :field="currentField"
    :full-width-content="fullWidthContent"
    :show-help-text="showHelpText"
  >
    <template #field>
      <div class="nova-cloudinary">
        <div :class="{ dark }">
          <div v-if="value?.length > 0" class="flex flex-row gap-2 flex-wrap w-full">
            <draggable
              v-model="value"
              class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-2 w-full"
              ghost-class="opacity-0"
              item-key="id"
              @end="drag = false"
              @start="drag = true"
              tag="ul"
              v-bind="dragOptions"
            >
              <template #item="{ element }">
                <FieldCard
                  :file="element"
                  :on-deselect="onDeselect"
                />
              </template>
            </draggable>
          </div>

          <div class="flex flex-row gap-2">
            <button
              class="relative flex flex-row shrink-0 items-center px-4 py-2 rounded-md border border-gray-300 dark:hover:border-blue-500 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 focus:z-10 focus:outline-none"
              type="button"
              @click="openCloudinaryModal"
            >
              <CloudinaryIcon
                class="-ml-1 mr-2 h-5 w-5 text-[#3448c5] dark:text-white"
              />
              {{ __('NovaCloudinary.openCloudinary') }}
            </button>
          </div>
        </div>
      </div>
    </template>
  </DefaultField>
</template>

<script setup>
import CloudinaryIcon from '@/components/CloudinaryIcon.vue'
</script>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova'
import draggable from 'vuedraggable'
import FieldCard from '@/components/FieldCard.vue'
import SyncWithDarkMode from '@/mixins/SyncWithDarkMode'
import { last, takeRight } from 'lodash'

export default {
  mixins: [DependentFormField, HandlesValidationErrors, SyncWithDarkMode],

  components: {
    FieldCard,
    draggable,
  },

  props: ['resourceName', 'resourceId', 'field'],

  data: () => ({
    drag: false,
    value: [],
    widget: null,
  }),

  mounted() {
    this.$cloudinaryInitWidget(this.currentField.widgetKey, this.currentField.configuration)
  },

  computed: {
    dragOptions() {
      return {
        animation: 200,
        disabled: this.value.length < 2,
        ghostClass: 'opacity-0',
      }
    },
  },

  methods: {
    onSyncedField() {
      this.$cloudinaryInitWidget(this.currentField.widgetKey, this.currentField.configuration)
    },

    setInitialValue() {
      this.value = this.currentField.value || []
    },

    fill(formData) {
      if (this.value?.length) {
        formData.append(
          this.currentField.attribute,
          JSON.stringify(this.value)
        )
      }
    },

    openCloudinaryModal() {
      this.$cloudinaryShowWidget(this.currentField.widgetKey, (data) => {
        if (this.currentField.configuration.multiple !== true) {
          this.value = [last(data.assets || [])]

          return
        }

        this.value = this.value.concat(data.assets || [])

        if (this.currentField.configuration.max_files && this.value.length > this.currentField.configuration.max_files) {
          this.value = takeRight(this.value, this.currentField.configuration.max_files)
        }
      })
    },

    onDeselect(file) {
      this.value = this.value.filter((item) => item.public_id !== file.public_id)
    },
  },
}
</script>
