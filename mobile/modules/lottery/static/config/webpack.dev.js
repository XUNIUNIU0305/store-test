var webpack = require('webpack');
var webpackMerge = require('webpack-merge');
var commonConfig = require('./webpack.common.js');
var path = require('path');

module.exports = webpackMerge(commonConfig, {
    devtool: 'source-map',

    output: {
        publicPath: '/',
        filename: '[name].js',
        chunkFilename: '[id].chunk.js'
    },

    devServer: {
        port: 8000,
        contentBase: path.resolve(__dirname, '../'),
        hot: true,
        proxy: {
            "/api": {
                target: "http://mobile.apex.com",
                changeOrigin: true,
                secure: false,
                pathRewrite: { "^/api": "" }
            }
        }
    }
});