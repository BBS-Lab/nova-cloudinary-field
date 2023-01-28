<template>
  <PanelItem :field="field" :index="index">
    <template v-if="field.value" v-slot:value>
      <div class="nova-cloudinary">
        <div :class="{ dark }">
          <ul v-if="field.value.length" class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-2 w-full" role="group">
            <template v-for="file in field.value" :key="file.public_id">
              <FieldCard
                :file="file"
                :on-copy="copy"
                @click.prevent.stop="() => selected = file"
              />
              <PreviewModal
                :file="file"
                v-if="!!selected && selected?.public_id === file.public_id"
                :close-modal="() => selected = null"
                :is-open="!!selected && selected?.public_id === file.public_id"
              />
            </template>
          </ul>
          <template v-else>
            <p>&mdash;</p>
          </template>
        </div>
      </div>
    </template>
  </PanelItem>
</template>

<script>
import { CopiesToClipboard } from 'laravel-nova'
import FieldCard from '@/components/FieldCard.vue'
import PreviewModal from '@/components/PreviewModal.vue'
import SyncWithDarkMode from '@/mixins/SyncWithDarkMode'

export default {
  mixins: [CopiesToClipboard, SyncWithDarkMode],

  components: {
    FieldCard,
    PreviewModal,
  },

  mounted() {
    console.log(this.field.value)
  },

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  data: () => ({
    selected: null,
  }),

  methods: {
    copy(file) {
      this.copyValueToClipboard(file.url)
    },
  },
}
</script>
