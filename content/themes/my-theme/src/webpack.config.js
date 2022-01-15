var path = require('path')
var webpack = require('webpack')
var ExtractTextPlugin = require('extract-text-webpack-plugin')
var OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')
var isProduction = process.env.NODE_ENV === "production";

var loaders = {
  css: ["vue-style-loader", "css-loader"],
  sass: ["vue-style-loader", "css-loader", "sass-loader"],
  scss: ["vue-style-loader", "css-loader", "sass-loader"],
};

if (isProduction) {

  for (var key in loaders) {
    var fallback = loaders[key].shift();
    loaders[key] = ExtractTextPlugin.extract({
      use: loaders[key],
      fallback: fallback
    });
  }

}


module.exports = {
  entry: './main.js',
  output: {
    path: path.resolve(__dirname, '../assets'),
    publicPath: 'http://localhost:8080/assets/',
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        test: /\.css$/,
        use: loaders.css
      },
      {
        test: /\.less$/,
        use: loaders.less
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders,
          esModule: false
        }
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.(png|jpg|gif|svg)$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]?[hash]'
        }
      }
    ]
  },
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  devServer: {
    historyApiFallback: true,
    noInfo: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
    },
    proxy: {
      "**": "http://localhost/"
    }
  },
  performance: {
    hints: false
  },
  devtool: '#eval-source-map',
  plugins: [
    new webpack.NamedModulesPlugin()
  ]
}

if ( isProduction ) {

  module.exports.devtool = '#source-map';

  module.exports.output.publicPath = './wp-content/themes/my-theme/assets/';

  // http://vue-loader.vuejs.org/en/workflow/production.html
  module.exports.plugins = (module.exports.plugins || []).concat([
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true,
      compress: {
        warnings: false
      }
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: true
    }),
    // extract css into its own file
    new ExtractTextPlugin('style.css'),
    // Compress extracted CSS. We are using this plugin so that possible
    // duplicated CSS from different components can be deduped.
    new OptimizeCSSPlugin(),
    // split vendor js into its own file
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      minChunks: function (module, count) {
        // any required modules inside node_modules are extracted to vendor
        return (
          module.resource &&
          /\.js$/.test(module.resource) &&
          module.resource.indexOf(
            path.join(__dirname, './node_modules')
          ) === 0
        )
      }
    })
  ])
}
