<?php
require_once __DIR__ . '/lib/functions.php';

return [
	'plugin' => [
		'name' => 'Metatags',
		'version' => '6.0',
		'dependencies' => [],
	],
	'bootstrap' => MetaTags::class,
];