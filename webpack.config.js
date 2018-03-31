/* eslint-env amd, node */

const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const FaviconsWebpackPlugin = require('favicons-webpack-plugin');

module.exports = {
  entry: {
    index: './assets/javascript/index.js',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build')
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        include: /assets/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            cacheDirectory: 'cache'
          }
        }
      },
      {
        test: /\.(gif|jpg|png|svg)$/,
        use: [
          'file-loader'
        ]
      },
      {
        test: /\.(eot|otf|ttf|woff|woff2)$/,
        use: [
          'file-loader'
        ]
      },
      {
        test: /\.less$/,
        use: ['style-loader', 'css-loader', 'less-loader']
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader']
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(['public/build']),
    new CopyWebpackPlugin([
      { from: './assets/files/humans.txt', to: '../../public' },
      { from: './assets/files/robots.txt', to: '../../public' }
    ]),
    new FaviconsWebpackPlugin({
      logo: './assets/images/pingoo.png',
      persistentCache: true,
      prefix: 'icons/',
      background: '#3737c8',
      icons: {
        android: true,
        appleIcon: true,
        appleStartup: true,
        coast: false,
        favicons: true,
        firefox: true,
        opengraph: false,
        twitter: false,
        yandex: false,
        windows: true
      }
    }),
    new webpack.HotModuleReplacementPlugin()
  ]
};
