<?php
require_once __DIR__ . '/lib/functions.php';

return [
	'plugin' => [
		'name' => 'Metatags',
		'version' => '4.0',
		'dependencies' => [],
	],
	'bootstrap' => MetaTags::class,
];