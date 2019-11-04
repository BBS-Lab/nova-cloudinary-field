<template>
    <panel-item :field="field">
        <template slot="value">
            <cld-image
                v-if="field.value"
                :cloudName="field.cloud_name"
                :publicId="field.value"
                width="300"
                class="mb-3"
                crop="fill"
            />
            <span v-else>Pas d'image sélectionnée</span>
        </template>
    </panel-item>
</template>

<script>
    import { CldImage } from 'cloudinary-vue'

    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],

        components: {
            'cld-image': CldImage
        }
    }
</script>
