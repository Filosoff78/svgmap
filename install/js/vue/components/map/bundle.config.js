const vuePlugin = require('../../../../../layout/node_modules/rollup-plugin-vue');
const commonjs = require('../../../../../layout/node_modules/rollup-plugin-commonjs');
const replace = require('rollup-plugin-replace');

module.exports = {
	input: 'src/pgk-map.js',
	output: 'dist/pgk-map.min.js',
	namespace: 'BX.Vue.Map',
	minification: true,
	plugins: {
		resolve: true,
		custom: [
			vuePlugin(),
			commonjs({
				extensions: ['.js', '.vue'],
			}),
			replace({
				'process.env.NODE_ENV': JSON.stringify( 'development' )
			}),
		],
	},
};
