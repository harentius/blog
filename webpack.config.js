const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build/')
  .setManifestKeyPrefix('build/')
  .cleanupOutputBeforeBuild()
  .enableSingleRuntimeChunk()
  .addEntry('article', './assets/js/article/index.js')
  .copyFiles({
    from: './assets/images',
    to: 'images/[path][name].[hash:8].[ext]',
  })
;
module.exports = Encore.getWebpackConfig();
