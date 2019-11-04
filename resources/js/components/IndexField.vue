<template>
    <cld-image
        v-if="field.value"
        :cloudName="field.cloud_name"
        :publicId="field.value"
        height="50"
        crop="fill"
    />
</template>

<script>
    import { CldImage } from 'cloudinary-vue'

    export default {
        props: ['resourceName', 'field'],

        components: {
            'cld-image': CldImage,
        }
    }
</script>
