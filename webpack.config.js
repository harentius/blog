const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build/')
  .setManifestKeyPrefix('build/')
  .cleanupOutputBeforeBuild()
  .enableSingleRuntimeChunk()
  // It is empty and not used, but encore force to define at least one file.
  .addEntry('common', './assets/css/main.less')
  .copyFiles({
    from: './assets/images',
    to: 'images/[path][name].[hash:8].[ext]',
  })
;
module.exports = Encore.getWebpackConfig();
