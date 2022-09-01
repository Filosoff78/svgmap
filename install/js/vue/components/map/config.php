<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

return [
	'js' => ['./dist/pgk-map.min.js', './src/base/gsap.js', './src/base/draggable.js'],
    'css' => ['./dist/pgk-map.min.css'],
	'rel' => [
		'main.polyfill.core',
		'ui.vue',
		'ui.vue.vuex',
		'ui.dialogs.messagebox',
	],
	'skip_core' => true,
];
