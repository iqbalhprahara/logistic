const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/ }));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('./public').mergeManifest();

const lodash = require("lodash");


mix.sass(__dirname + '/resources/vendor/skote/scss/app.scss', "/vendor/skote/css").options({processCssUrls: false}).minify("./public/vendor/skote/css/app.css");


//copying demo pages related assets
var app_pages_assets = {
    js: [
        __dirname + "/resources/vendor/skote/js/pages/dashboard.init.js"
    ]
};

lodash(app_pages_assets).forEach(function (assets, type) {
    for (let i = 0; i < assets.length; ++i) {
        mix.js(assets[i],  "vendor/skote/js/pages");
    };
});

mix.js(__dirname + '/resources/vendor/skote/js/app.js', "vendor/skote/js/app.js");

if (mix.inProduction()) {
    mix.version();
}
