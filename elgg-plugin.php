<?php
return [
	'plugin' => [
		'name' => 'Metatags',
		'version' => '7.0',
	],
	'settings' => [
		'mainpage_title' => elgg_get_site_entity()->name . " - Social Network",
    'mainpage_description' => elgg_get_site_entity()->name . " is a social network for people with interest in ....",
    'mainpage_keywords' => "social network, join, register, members, " . elgg_get_site_entity()->name,
    'mainpage_image' => '',
	],
	'view_extensions' => [
		'page/elements/head' => [
			'metatagsgen/metatags' => [],
		],
	],
	'events' => [
		'view_vars' => [
			'all' => [
				'\MetaTags\Events::GetGUID' => [],
			]
		],
	],
];