/* eslint-env amd, node */

const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const HtmlWebpackPlugin = require('html-webpack-plugin');
const WebappWebpackPlugin = require('webapp-webpack-plugin');

module.exports = (env, argv) => ({
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    port: 8080,
    stats: {
      colors: true
    }
  },
  entry: {
    index: './src/index.jsx',
  },
  output: {
    filename: argv.mode === 'development' ? '[name].js' : '[name].[chunkhash].js',
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
        use: ['file-loader']
      },
      {
        test: /\.(eot|otf|ttf|woff|woff2)$/,
        use: ['file-loader']
      },
      {
        test: /\.less$/,
        use: ExtractTextPlugin.extract({
          use: [{
            loader: "css-loader"
          }, {
            loader: "less-loader"
          }],
          fallback: "style-loader"
        })
      },
      {
        test: /\.css$/,
        use: ExtractTextPlugin.extract({
          use: [{
            loader: "css-loader"
          }],
          fallback: "style-loader"
        })
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(['public']),
    new CopyWebpackPlugin([
      {from: './assets/files/humans.txt'},
      {from: './assets/files/robots.txt'}
    ]),
    new ExtractTextPlugin({
      filename: "[name].[chunkhash].css",
      disable: argv.mode === 'development'
    }),
    new HtmlWebpackPlugin({
      inject: false,
      lang: 'en',
      meta: [{
        name: 'description',
        content: 'A basic skeleton for ES6 web applications'
      }],
      mobile: true,
      minify: {
        removeComments: argv.mode === 'production',
        collapseWhitespace: argv.mode === 'production'
      },
      template: './src/templates/index.ejs',
      title: 'My ES6 application skeleton'
    }),
    new WebappWebpackPlugin({
      logo: './assets/images/pingoo.png',
      inject: true,
      cache: 'cache/favicons',
      favicons: {
        appName: 'app-skeleton',
        appDescription: 'A basic skeleton for ES6 web applications',
        developerName: 'Damien Carcel',
        developerURL: 'https://github.com/damien-carcel/',
        background: '#3737c8',
        theme_color: '#373737',
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
      }
    })
  ],
  resolve: {
    extensions: ['.js', '.json', '.jsx']
  }
});
