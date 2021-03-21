const path = require('path');
const assets = path.resolve(__dirname, './public/assets');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const mode = 'production' === process.env.NODE_ENV ? 'production' : 'development';

module.exports = [
    {
        mode: mode,
        entry: {
            styles: './frontend/css/styles.scss'
        },
        output: {
            filename: '[name].js',
            path: assets
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: '[name].css',
                chunkFilename: '[name].css'
            })
        ],
        module: {
            rules: [
                {
                    test: /\.(sc|c)ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'sass-loader'
                    ],
                }
            ],
        }
    },
    {
        mode: mode,
        entry: {
            index: './frontend/js/index.js'
        },
        output: {
            filename: '[name].js',
            chunkFilename: '[name].js',
            path: assets
        }
    }
];
