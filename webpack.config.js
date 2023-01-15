const encore = require('@symfony/webpack-encore');
const path = require('path');

if (!encore.isRuntimeEnvironmentConfigured()) {
    encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

module.exports = encore
    .setOutputPath('./public/dist/')
    .setPublicPath('/dist')
    .configureImageRule({
        type: 'asset',
    })
    .copyFiles({
         from: './resources/assets/images',
         to: 'images/[path][name].[hash:8].[ext]',
         pattern: /\.(png|jpg|jpeg|svg)$/,
     })
    .addEntry('app', './resources/assets/app.js')
    .splitEntryChunks()
    .configureSplitChunks(splitChunks => {
         splitChunks.minSize = 0;
    })
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!encore.isProduction())
    .enableVersioning(encore.isProduction())
    .configureBabel(config => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
        config.plugins.push('@babel/plugin-proposal-private-methods');
        config.plugins.push('@babel/plugin-transform-flow-strip-types');
    })
    .configureBabelPresetEnv(config => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .enablePostCssLoader()
    .enableIntegrityHashes(encore.isProduction())

    // -------------------------------------------------------------------------
    //  BUILD
    // -------------------------------------------------------------------------

    .getWebpackConfig()
;
