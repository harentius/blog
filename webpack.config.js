const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('src/Harentius/FolkprogBundle/Resources/public/js/build/')
  .setPublicPath('/bundles/harentiusfolkprog/')
  .setManifestKeyPrefix('bundles/harentiusfolkprog/')
  .cleanupOutputBeforeBuild()
  .addEntry('common-folkprog', './src/Harentius/FolkprogBundle/Resources/js/common-folkprog.js')
  .addStyleEntry('main', './src/Harentius/FolkprogBundle/Resources/css/main.less')
  .enableLessLoader()
  .enableVersioning()
;

module.exports = Encore.getWebpackConfig();
