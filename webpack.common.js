const babelPresets = ['es2015', 'es2016', 'es2017'];
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const FaviconsWebpackPlugin = require('favicons-webpack-plugin');

module.exports = {
  entry: {
    app: './src/app.js',
    print: './src/print.js'
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
            presets: babelPresets,
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
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(['dist']),
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
    })
  ]
};
