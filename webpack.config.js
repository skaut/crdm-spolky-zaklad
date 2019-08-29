const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const LiveReloadPlugin = require('webpack-livereload-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const md5File = require('md5-file');

LiveReloadPlugin.prototype.done = function done(stats) {
    this.fileHashes = this.fileHashes || {}

    const fileHashes = {}
    for (let file of Object.keys(stats.compilation.assets)) {
        fileHashes[file] = md5File.sync(stats.compilation.assets[file].existsAt)
    }

    const toInclude = Object.keys(fileHashes).filter((file) => {
        if (this.ignore && file.match(this.ignore)) {
            return false
        }
        return !(file in this.fileHashes) || this.fileHashes[file] !== fileHashes[file]
    })

    if (this.isRunning && toInclude.length) {
        this.fileHashes = fileHashes
        console.log('Live Reload: Reloading ' + toInclude.join(', '))
        setTimeout(
            function onTimeout() {
                this.server.notifyClients(toInclude)
            }.bind(this)
        )
    }
}

module.exports = {
    mode: 'production',
    entry: {
        app: './src/js/index.js',
        admin: './src/js/admin/admin.js',
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'dist/assets/js')
    },
    devtool: 'source-map',
    watchOptions: {
        ignored: '/node_modules/'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                        cacheDirectory: true,
                        sourceMap: true
                    }
                }
            },
            {
                test: /\.(css|s?[ac]ss)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                            sourceMap: true
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            ident: 'postcss',
                            sourceMap: true,
                            plugins: function (loader) {
                                return [
                                    require('postcss-import')({root: loader.resourcePath}),
                                    require('postcss-preset-env')({
                                        stage: 0,
                                        autoprefixer: {
                                            grid: true
                                        }
                                    }),
                                    require('cssnano')({
                                        'safe': true,
                                        'calc': false
                                    })
                                ];
                            }
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            }
        ],
    },
    plugins: [
        new CleanWebpackPlugin({
            verbose: true
        }),
        new CopyWebpackPlugin([{
            from: './src/img',
            to: './../img'
        },{
            from: './src/php',
            to: './../../'
        },{
            from: './src/assets',
            to: './../../'
        },{
            from: './vendor',
            to: './../../vendor'
        }]),
        new ImageminPlugin(),
        new MiniCssExtractPlugin({
            filename: '../css/[name].css'
        }),
        new LiveReloadPlugin()
    ],
    externals: {
        jquery: 'jQuery'
    },
    optimization: {
        minimizer: [
            new UglifyJsPlugin({
                uglifyOptions: {
                    output: {
                        comments: false
                    }
                },
                sourceMap: true
            })
        ]
    }
};