const widgets = new Map

export default {
  install: (app) => {
    app.config.globalProperties.$cloudinaryCallback = null

    app.config.globalProperties.$cloudinaryInitWidget = (key, options) => {
      if (widgets.has(key)) {
        return
      }

      widgets.set(
        key,
        cloudinary.createMediaLibrary(options, {
          insertHandler: (data) => {
            if (app.config.globalProperties.$cloudinaryCallback) {
              app.config.globalProperties.$cloudinaryCallback(data)
            }
          }
        })
      )
    }

    app.config.globalProperties.$cloudinaryShowWidget = (key, callback) => {
      if (!widgets.has(key)) {
        return
      }

      app.config.globalProperties.$cloudinaryCallback = callback
      widgets.get(key).show()
    }
  }
}
