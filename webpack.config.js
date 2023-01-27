const path = require('path')

module.exports = () => {
  const prefix = process.env.MIX_NOVA_PREFIX || '../..'

  return {
    externals: {
      vue: 'Vue',
      'laravel-nova': 'LaravelNova',
    },

    resolve: {
      fallback: { 'path': require.resolve('path-browserify') },
      alias: {
        '@': path.join(__dirname, '/resources/js'),
      },
    },

    output: {
      uniqueName: 'nova-cloudinary',
    },
  }
}
