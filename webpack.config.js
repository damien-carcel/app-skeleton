/* eslint-env amd, node */

const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const WebappWebpackPlugin = require('webapp-webpack-plugin');

module.exports = (env, argv) => ({
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    port: 9000,
    stats: {
      colors: true
    }
  },
  output: {
    path: path.resolve(__dirname, 'public')
  },
  module: {
    rules: [
      {
        test: /\.js$/,
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
      {from: './assets/files/humans.txt'},
      {from: './assets/files/robots.txt'}
    ]),
    new HtmlWebpackPlugin({
      inject: false,
      lang: 'en',
      meta: [
        {
          name: 'description',
          content: 'A basic skeleton for ES6 web applications'
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
  ]
});
