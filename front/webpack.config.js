/* eslint-env amd, node */

const path = require('path');
const webpack = require('webpack');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const FaviconsWebpackPlugin = require('favicons-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const WebappWebpackPlugin = require('webapp-webpack-plugin');

module.exports = (env, argv) => ({
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    port: 8080,
    stats: {
      colors: true,
    },
  },
  entry: {
    index: './src/index.tsx',
  },
  output: {
    filename: argv.mode === 'development' ? '[name].js' : '[name].[chunkhash].js',
    path: path.resolve(__dirname, 'public'),
  },
  module: {
    rules: [
      {
        test: /\.(tsx?)|(js)$/,
        include: /src/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            cacheDirectory: 'cache',
          },
        },
      },
      {
        test: /\.(gif|jpg|png|svg)$/,
        use: [
          'file-loader',
          {
            loader: 'image-webpack-loader',
            options: {
              disable: argv.mode === 'development',
            },
          },
        ],
      },
      {
        test: /\.(eot|otf|ttf|woff|woff2)$/,
        use: ['file-loader'],
      },
      {
        test: /\.(le|c)ss$/,
        use: [
          argv.mode === 'development' ? 'style-loader' : MiniCssExtractPlugin.loader,
          'css-loader',
          'less-loader',
        ],
      },
    ],
  },
  optimization: {
    minimizer: [
      new TerserPlugin({
        cache: true,
        parallel: true,
        sourceMap: true,
      }),
      new OptimizeCSSAssetsPlugin({}),
    ],
  },
  plugins: [
    new webpack.EnvironmentPlugin(['API_BASE_URL']),
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['**/*', '!.gitkeep'],
    }),
    new CopyWebpackPlugin([
      {from: './assets/files/humans.txt'},
      {from: './assets/files/robots.txt'},
    ]),
    new FaviconsWebpackPlugin('./assets/images/favicon.png'),
    new HtmlWebpackPlugin({
      inject: false,
      lang: 'en',
      meta: [{
        name: 'description',
        content: 'A basic skeleton for React web applications',
      }],
      mobile: true,
      template: require('html-webpack-template'),
      title: 'My React application skeleton',
    }),
    new MiniCssExtractPlugin({
      filename: argv.mode === 'development' ? '[name].css' : '[name].[chunkhash].css',
      chunkFilename: argv.mode === 'development' ? '[id].css' : '[id].[chunkhash].css',
    }),
    new WebappWebpackPlugin({
      logo: './assets/images/pingoo.png',
      inject: true,
      cache: 'cache/favicons',
      favicons: {
        appName: 'app-skeleton',
        appDescription: 'A basic skeleton for React web applications',
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
          windows: true,
        },
      },
    }),
  ],
  resolve: {
    extensions: ['.js', '.json', '.jsx', '.ts', '.tsx'],
  },
});
