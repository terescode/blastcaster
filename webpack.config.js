/*global require, module*/

var webpack = require('webpack');

module.exports = {
  entry: './admin/js/app.js',
  output: {
    path: './admin/js',
    filename: 'bundle.js'
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        loader: 'babel-loader',
        query: {
          presets: ['react', 'es2015'],
          plugins: ['transform-object-rest-spread']
        }
      }
    ]
  },
  plugins: [
  ]
};