var path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
var SpritesmithPlugin = require('webpack-spritesmith');


// change these variables to fit your project
const outputPath = './assets';
const outputPrefix = 'pnwc';
const entryPoints = {
    //admin-settings: ['./src/js/admin-settings.js', './src/scss/admin-settings.scss'],
    //frontend: ['./src/scss/frontend.scss']
    //admin: ['./src/scss/admin.scss','./src/js/admin.js'],
    frontend: ['./src/scss/frontend.scss', './src/js/frontend.js']
};

// Rules
const rules = [
    {
        test: /\.scss$/i,
        use: [
            MiniCssExtractPlugin.loader,
            {loader: 'css-loader', options: {url: true, sourceMap: true}},
            {
                loader: "postcss-loader",
                options: {
                    postcssOptions: {
                        plugins: [
                            [
                                "postcss-preset-env",
                                {
                                    browsers: 'defaults'
                                },
                            ],
                        ],
                    },
                },
            },
            'sass-loader',
        ]
    },
    {
        test: /\.(png|svg|jpg|jpeg|gif)$/i,
        type: 'asset/resource',
        generator: {
            publicPath: "img/",
            outputPath: 'img',
        },
    },
    {
        exclude: /node_modules/,
        test: /\.jsx?$/,
        loader: 'babel-loader',
        options: {
            presets: ["@babel/preset-env"],
        }
    }
];

// Development
const devConfig = {
    entry: entryPoints,
    output: {
        publicPath: 'auto',
        //publicPath: '/',
        path: path.resolve(__dirname, outputPath),
        filename: 'js/'+outputPrefix+'-[name].js',
        chunkFilename: 'js/modules/'+outputPrefix+'-[name].js',
    },
    plugins: [

        new MiniCssExtractPlugin({
            filename: 'css/'+outputPrefix+'-[name].css',
        }),

        // Uncomment this if you want to use CSS Live reload
        new BrowserSyncPlugin({
            port: 3000,
            proxy: 'http://test.wpdev.com/',
            files: [outputPath + '/css/*.css'],
            injectCss: true,
        }, {reload: false,}),

    ],
    module: {
        rules: rules
    },
    devtool: 'source-map',

};

// Production
const prodConfig = {
    entry: entryPoints,
    output: {
        path: path.resolve(__dirname, outputPath),
        filename: 'js/'+outputPrefix+'-[name].min.js',
        chunkFilename: 'js/modules/'+outputPrefix+'-[name].min.js',
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/'+outputPrefix+'-[name].min.css',
        }),
    ],
    module: {
        rules: rules
    },
    optimization: {
        chunkIds: 'named',
    },

};

// Exports
module.exports = (env, argv) => {
    switch (argv.mode) {
        case 'production':
            return prodConfig;
        default:
            return devConfig;
    }
}