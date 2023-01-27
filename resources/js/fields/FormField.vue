<template>
  <DefaultField
    :errors="errors"
    :field="field"
    :full-width-content="fullWidthContent"
    :show-help-text="showHelpText"
  >
    <template #field>
      <div class="nova-cloudinary">
        <div>
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
              <img
                src="https://res.cloudinary.com/cloudinary-marketing/image/upload/c_scale,w_45/creative_source/Logo/Cloud%20Glyph/cloudinary_cloud_glyph_regular.svg"
                alt=""
                aria-hidden="true"
                class="-ml-1 mr-2 h-5 w-5 text-gray-400 dark:text-gray-200"
              >
              {{ __('NovaCloudinary.openCloudinary') }}
            </button>
          </div>
        </div>
      </div>
    </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import draggable from 'vuedraggable'
import FieldCard from '@/components/FieldCard.vue'

export default {
  mixins: [FormField, HandlesValidationErrors],

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
    this.$cloudinaryInitWidget(this.field.widgetKey, this.field.configuration)
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
    setInitialValue() {
      this.value = this.field.value || []
    },

    fill(formData) {
      if (this.value?.length) {
        formData.append(
          this.field.attribute,
          JSON.stringify(this.value)
        )
      }
    },

    openCloudinaryModal() {
      this.$cloudinaryShowWidget(this.field.widgetKey, (data) => {
        this.value = this.value.concat(data.assets || [])
      })
    },

    onDeselect(file) {
      this.value = this.value.filter((item) => item.public_id !== file.public_id)
    },
  },
}
</script>
