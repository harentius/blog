const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('web/build/')
  .setPublicPath('/build/')
  .setManifestKeyPrefix('build/')
  .cleanupOutputBeforeBuild()
  .addEntry('common-folkprog', './src/Harentius/FolkprogBundle/Resources/js/common-folkprog.js')
  .addStyleEntry('main', './src/Harentius/FolkprogBundle/Resources/css/main.less')
  .enableLessLoader()
  .enableVersioning()
;

module.exports = Encore.getWebpackConfig();
