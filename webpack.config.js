// webpack.config.js
const webpack = require('webpack')
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin'); //installed via npm
const path = require('path')

const config = {
	context: path.resolve(__dirname, 'sources'),
	entry: {
		"admin": './admin.js'
	},
	output: {
		path: path.resolve(__dirname, 'htdocs/js_dist'),
		filename: '[name].js'
	},
	plugins: [
        new CleanWebpackPlugin([
            path.resolve(__dirname, 'htdocs/assets/calendrier/'),
            path.resolve(__dirname, 'htdocs/js_dist')
		]),
		new CopyWebpackPlugin([
			{ from: path.resolve(__dirname, 'node_modules/tablefilter/dist') },

            { from: path.resolve(__dirname, 'node_modules/jquery/dist/jquery.min.js'), to: path.resolve(__dirname, 'htdocs/assets/calendrier/js/jquery.min.js') },
            { from: path.resolve(__dirname, 'node_modules/angular/lib/angular.min.js'), to: path.resolve(__dirname, 'htdocs/assets/calendrier/js/angular.min.js') },
            { from: path.resolve(__dirname, 'node_modules/bootstrap/dist/js/bootstrap.min.js'), to: path.resolve(__dirname, 'htdocs/assets/calendrier/js/bootstrap.min.js') },
            { from: path.resolve(__dirname, 'node_modules/bootswatch/yeti/bootstrap.min.css'), to: path.resolve(__dirname, 'htdocs/assets/calendrier/css/bootstrap.min.css') },
            { from: path.resolve(__dirname, 'node_modules/bootswatch/fonts/'), to: path.resolve(__dirname, 'htdocs/assets/calendrier/fonts/') }
		])
	]
	/*,
	module: {
		rules: [{
			test: /\.js$/,
			include: path.resolve(__dirname, 'src'),
			use: [{
				loader: 'babel-loader',
				options: {
					presets: [
						['es2015', { modules: false }]
					]
				}
			}]
		}]
	}*/
}

module.exports = config