<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <span v-if="!field.api_key">API key missing, please specify it in the .env file as CLOUDINARY_API_KEY</span>
            <span v-else-if="!field.cloud_name">Cloud name missing, please specify it in the .env file as CLOUDINARY_CLOUD_NAME</span>
            <span v-else-if="!field.username">Username missing, please specify it in the .env file as CLOUDINARY_USERNAME</span>
            <div v-else class="cloudinary_field">
                <cld-image
                    v-if="value"
                    :cloudName="field.cloud_name"
                    :publicId="value"
                    width="300"
                    class="mb-3"
                    crop="fill"
                />
                <button
                    v-if="value"
                    type="button"
                    tabindex="0"
                    class="cursor-pointer dim btn btn-link text-primary inline-flex items-center mb-3" dusk="photo-delete-link"
                    @click.prevent="resetValue"
                >
                    <!-- Trash icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                    <span class="class ml-2 mt-1"> Supprimer </span>
                </button>
                <button
                    type="button"
                    class="btn btn-default btn-primary"
                    @click.prevent="openMediaLibrary"
                >
                    Choisir l'image
                </button>
                <input
                    :id="field.name"
                    type="hidden"
                    v-model="value"
                />
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import { CldImage } from 'cloudinary-vue';

export default {
    data() {
        return {
            uploadWidget: null,
        }
    },

    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        'cld-image': CldImage,
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        openMediaLibrary() {
            this.uploadWidget.show()
        },

        resetValue() {
            this.value = ''
        }
    },

    mounted() {;
        this.uploadWidget = cloudinary.createMediaLibrary({
                cloud_name: this.field.cloud_name,
                api_key: this.field.api_key,
                signature: this.field.signature,
                username: this.field.username,
                timestamp: this.field.timestamp,
                multiple: false,
            }, {
                insertHandler: (data) => {
                    if (data.assets.length > 0) {
                        this.value = data.assets[0].public_id
                    }
                }
            }
        )
    }
}
</script>

<style scoped>
    .cloudinary_field {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
    }
</style>
