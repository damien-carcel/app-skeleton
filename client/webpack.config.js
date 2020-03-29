/* eslint-env amd, node */

const path = require('path');
const webpack = require('webpack');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FaviconsWebpackPlugin = require('favicons-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = (env, argv) => ({
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    host: '0.0.0.0',
    port: parseInt(process.env.CLIENT_PORT || 8080),
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
      cleanOnceBeforeBuildPatterns: ['**/*', '!humans.txt', '!robots.txt'],
    }),
    new HtmlWebpackPlugin({
      appMountId: 'app',
      lang: 'en',
      meta: [{
        name: 'description',
        content: 'A basic skeleton for React web applications',
      }],
      mobile: true,
      template: require('html-webpack-template'),
      title: 'My React application skeleton',
      unsupportedBrowser: true,
    }),
    new FaviconsWebpackPlugin('./assets/images/favicon.png'),
    new ManifestPlugin(),
    new MiniCssExtractPlugin({
      filename: argv.mode === 'development' ? '[name].css' : '[name].[chunkhash].css',
      chunkFilename: argv.mode === 'development' ? '[id].css' : '[id].[chunkhash].css',
    }),
  ],
  resolve: {
    extensions: ['.js', '.json', '.jsx', '.ts', '.tsx'],
  },
});
