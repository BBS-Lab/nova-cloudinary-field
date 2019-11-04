Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-cloudinary-field', require('./components/IndexField'))
    Vue.component('detail-nova-cloudinary-field', require('./components/DetailField'))
    Vue.component('form-nova-cloudinary-field', require('./components/FormField'))
})
