const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('web/assets/build/')
  .setPublicPath('/assets/build/')
  .setManifestKeyPrefix('assets/build/')
  .cleanupOutputBeforeBuild()
  .addEntry('common-folkprog', './src/Harentius/FolkprogBundle/Resources/js/common-folkprog.js')
  .addStyleEntry('main', './src/Harentius/FolkprogBundle/Resources/css/main.less')
  .enableLessLoader()
  .enableVersioning()
;

module.exports = Encore.getWebpackConfig();
