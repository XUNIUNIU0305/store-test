var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var HtmlWebpackPlugin = require('html-webpack-plugin');
var path = require('path');
var ENV = process.env.NODE_ENV;

module.exports = {
    entry: {
        'index': './src/scripts/index',
        'game': './src/scripts/game',
        'gift': './src/scripts/gift',
        'record': './src/scripts/record'
    },

    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: "babel-loader"
            },
            {
                test: /\.(html)$/,
                use: {
                    loader: 'html-loader'
                }
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: ['css-loader', 'postcss-loader', 'sass-loader']
                })

            },
            {
                test: /\.(png|jpg|svg|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            outputPath: 'images/lottery/',
                            name: '[name].[ext]'
                        }
                    }
                ]
            }
        ],
    },

    resolve: {
        extensions: ['.js', '.scss']
    },

    plugins: [

        new webpack.optimize.CommonsChunkPlugin({
            // name: ['index', 'game', 'record']
            name: 'vendors', // 将公共模块提取，生成名为`vendors`的chunk
            chunks: ['index', 'game', 'record'], //提取哪些模块共有的部分
        }),

        new ExtractTextPlugin('css/[name].css'),

        new HtmlWebpackPlugin({
            template: 'src/index.html',
            chunks: ['vendors', 'index']
        }),

        new HtmlWebpackPlugin({
            filename: 'game.html',
            template: 'src/game.html',
            chunks: ['vendors', 'game']
        }),

        new HtmlWebpackPlugin({
            filename: 'gift.html',
            template: 'src/gift.html',
            chunks: ['vendors', 'gift']
        }),

        new HtmlWebpackPlugin({
            filename: 'gift2.html',
            template: 'src/gift2.html',
            chunks: ['vendors', 'gift']
        }),

        new HtmlWebpackPlugin({
            filename: 'record.html',
            template: 'src/record.html',
            chunks: ['vendors', 'record']
        })
    ]
};