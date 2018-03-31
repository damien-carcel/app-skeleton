/* eslint-env amd, node */

const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const FaviconsWebpackPlugin = require('favicons-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = (env, argv) => ({
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    port: 9000,
    hot: true
  },
  entry: {
    index: './src/index.jsx',
  },
  output: {
    path: path.resolve(__dirname, 'public')
  },
  module: {
    rules: [
      {
        test: /\.jsx$/,
        include: /src/,
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
    new CleanWebpackPlugin(['public']),
    new CopyWebpackPlugin([
      { from: './assets/files/humans.txt' },
      { from: './assets/files/robots.txt' }
    ]),
    new FaviconsWebpackPlugin({
      logo: './assets/images/pingoo.png',
      persistentCache: true,
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
    new HtmlWebpackPlugin({
      inject: false,
      lang: 'en',
      meta: [
        {
          name: 'description',
          content: 'A better default template for html-webpack-plugin.'
        }
      ],
      mobile: true,
      minify: {
        removeComments: argv.mode === 'production',
        collapseWhitespace: argv.mode === 'production'
      },
      template: './src/templates/index.ejs',
      title: 'My ES6 application skeleton'
    }),
    new webpack.HotModuleReplacementPlugin()
  ],
  resolve: {
    extensions: ['.js', '.json', '.jsx']
  }
});
