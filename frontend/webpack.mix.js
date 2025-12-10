const mix = require("laravel-mix");
const glob = require("glob");
const ESLintPlugin = require("eslint-webpack-plugin");
const StyleLintPlugin = require("stylelint-webpack-plugin");

const webPath = "build";
mix.setPublicPath(webPath);

mix.js("src/js/common.js", `${webPath}/js/common.js`);

mix
    .sass("src/scss/styles.scss", `${webPath}/css/styles.css`)
    .options({
        processCssUrls: false
    })
    .sourceMaps(true, "inline-source-map");

mix.copyDirectory("src/css", `${webPath}/css`);
mix.copyDirectory("src/font", `${webPath}/font`);
mix.copyDirectory("src/img", `${webPath}/img`);
mix.webpackConfig({
    plugins: [
        new ESLintPlugin(),
        new StyleLintPlugin({
            exclude: [
                "build/**",
                "src/css/cake.css",
                "src/css/fonts.css",
                "src/css/home.css",
                "src/css/milligram.min.css",
                "src/css/normalize.min.css",
            ],
        }),
    ],
});
