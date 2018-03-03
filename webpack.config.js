const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('web/build/')
  .setPublicPath('/build/')
  .setManifestKeyPrefix('build/')
  .cleanupOutputBeforeBuild()
  .addStyleEntry('main', './src/Harentius/FolkprogBundle/Resources/css/main.less')
  .enableLessLoader()
  .enableVersioning()
;

module.exports = Encore.getWebpackConfig();
