const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')

    //from css folder
    .addEntry('custom.css', './assets/css/custom.style.css')
    .addEntry('email-editor.css', './assets/css/email-editor.css')
    .addEntry('return.css', './assets/css/return.css')
    .addEntry('style.bundle.css', './assets/css/style.bundle.css')

    //from build foler
    .addEntry('build.css', './assets/build/css/custom.css')
    // .addEntry('build.vendor.bootstrap.bundle', './assets/build/vendor/bootstrap.bundle.min.js')
    .addEntry('build.vendor.jquery', './assets/build/vendor/jquery.js')
    .addEntry('build.vendor.jquery.min', './assets/build/vendor/jquery.min.js')
    // //end build folder


    // //from plugins folder
    .addEntry('plugins.custom.datatables.datatables.css', './assets/plugins/custom/datatables/datatables.bundle.css')
    // .addEntry('plugins.custom.datatables.datatables.js', './assets/plugins/custom/datatables/datatables.bundle.js')
    // .addEntry('plugins.custom.fullcalendar.css', './assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')
    // .addEntry('plugins.custom.fullcalendar.js', './assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')
    .addEntry('plugins.global.plugins.bundle.css', './assets/plugins/global/plugins.bundle.css')
    .addEntry('plugins.global.plugins.bundle.js', './assets/plugins/global/plugins.bundle.js')
    // //end folder plugins


    // //from folder js
    .addEntry('js.custom.apps-chat.chat.js', './assets/js/custom/apps-chats/chat.js')
    .addEntry('js.custom.customers.list.export.js', './assets/js/custom/customers/list/export.js')
    .addEntry('js.custom.customers.list.list.js', './assets/js/custom/customers/list/list.js')
    .addEntry('js.custom.customers.add.js', './assets/js/custom/customers/add.js')
    .addEntry('js.custom.intro.js', './assets/js/custom/intro.js')
    .addEntry('js.custom.widgets.js', './assets/js/custom/widgets.js')
    .addEntry('js.custom.modals.create-app.js', './assets/js/custom/modals/create-app.js')
    .addEntry('js.custom.modals.upgrade-plan.js', './assets/js/custom/modals/upgrade-plan.js')
    .addEntry('js.custom.modals.users-search.js', './assets/js/custom/modals/users-search.js')
    .addEntry('js.scripts.bundle.js', './assets/js/scripts.bundle.js')
    .addEntry('js.widgets.js', './assets/js/widgets.bundle.js')
    //edd folder js
    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
