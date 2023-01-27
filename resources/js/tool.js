// import Tool from '@/pages/Tool'
import IndexField from '@/fields/IndexField'
import DetailField from '@/fields/DetailField'
import FormField from '@/fields/FormField'
import Cloudinary from '@/plugins/cloudinary'

Nova.booting(app => {
  app.use(Cloudinary)

  // Nova.inertia('NovaCloudinary', Tool)

  app.component('index-nova-cloudinary-field', IndexField)
  app.component('detail-nova-cloudinary-field', DetailField)
  app.component('form-nova-cloudinary-field', FormField)
})
