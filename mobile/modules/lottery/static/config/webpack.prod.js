var webpack = require('webpack');
var webpackMerge = require('webpack-merge');
var commonConfig = require('./webpack.common.js');
var path = require('path');

const ENV = process.env.NODE_ENV;


module.exports = webpackMerge(commonConfig, {
    // devtool: 'source-map',

    output: {
        path: path.resolve(__dirname, '../../views/assets'),
        publicPath: '/',
        filename: 'js/[name].js',
        chunkFilename: 'js/[id].chunk.js'
    },

    plugins: [

        new webpack.optimize.UglifyJsPlugin({
            mangle: {
                eval: true,
                toplevel: true,
            },
            comments: false
        }),
        new webpack.DefinePlugin({
            'process.env': {
                'ENV': JSON.stringify(ENV)
            }
        })
    ]
});