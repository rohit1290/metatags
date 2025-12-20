<?php
/**
* Elgg Metatags generator plugin (Elgg 6.3+)
*
* Supports user_metatag & group_metatag
* Adds JSON-LD schema.org for SEO
* @license GNU GPL v2
*/

$title = (string) elgg_extract('title', $vars, elgg_get_site_entity()->name);
$site = elgg_get_site_entity();
$site_name = $site->getDisplayName();
$site_url = $site->getURL();

$guid = elgg_extract('guid', $vars);
$cguid = elgg_extract('container_guid', $vars);

$entity = null;
if ($cguid) {
	$entity = get_entity($cguid);
} elseif ($guid) {
	$entity = get_entity($guid);
} else {
	$entity = elgg_extract('entity', $vars);
}

$page_owner = elgg_get_page_owner_entity();
$meta_description = (string) elgg_get_plugin_setting("mainpage_description", "metatags", '');
$mainpage_image = elgg_get_plugin_setting("mainpage_image", "metatags");
if (!empty($mainpage_image)) {
	if (!filter_var($mainpage_image, FILTER_VALIDATE_URL)) {
		$mainpage_image = elgg_get_simplecache_url($mainpage_image);
	}
}
$author           = $site_name;
$tags             = [];
$jsonld           = []; // schema.org data

/**
 * USER METATAGS
 */
if ($page_owner instanceof ElggUser) {
	$title = $page_owner->getDisplayName() . " - " . $site_name;
	$author = $page_owner->getDisplayName();

	// Description
	if (!empty($page_owner->briefdescription)) {
		$meta_description = elgg_get_excerpt((string) $page_owner->briefdescription, 160);
	} elseif (!empty($page_owner->description)) {
		$meta_description = elgg_get_excerpt((string) $page_owner->description, 160);
	}

	// Profile photo
	$mainpage_image = $page_owner->getIconURL(['size' => 'large']);

	// Keywords
	$tags[] = $page_owner->getDisplayName();
	if (!empty($page_owner->skills)) {
		$tags = array_merge($tags, (array) $page_owner->skills);
	}
	if (!empty($page_owner->interests)) {
		$tags = array_merge($tags, (array) $page_owner->interests);
	}
	if (!empty($page_owner->location)) {
		$tags[] = $page_owner->location;
	}

	// JSON-LD Person schema
	$jsonld = [
		"@context" => "https://schema.org",
		"@type" => "Person",
		"name" => $page_owner->getDisplayName(),
		"description" => $meta_description,
		"url" => elgg_get_current_url(),
		"image" => $mainpage_image,
		"memberOf" => [
			"@type" => "Organization",
			"name" => $site_name,
			"url" => $site_url,
		],
	];
}

/**
 * GROUP METATAGS
 */
elseif ($page_owner instanceof ElggGroup) {
	$title = $page_owner->getDisplayName() . " - " . $site_name;
	$author = $page_owner->getOwnerEntity() instanceof ElggUser
		? $page_owner->getOwnerEntity()->getDisplayName()
		: $site_name;

	if (!empty($page_owner->description)) {
		$meta_description = elgg_get_excerpt((string) $page_owner->description, 160);
	}

	$mainpage_image = $page_owner->getIconURL(['size' => 'large']);

	$tags[] = $page_owner->getDisplayName();
	if (!empty($page_owner->tags)) {
		$tags = array_merge($tags, (array) $page_owner->tags);
	}

	// JSON-LD Organization schema
	$jsonld = [
		"@context" => "https://schema.org",
		"@type" => "Organization",
		"name" => $page_owner->getDisplayName(),
		"description" => $meta_description,
		"url" => elgg_get_current_url(),
		"image" => $mainpage_image,
		"parentOrganization" => [
			"@type" => "Organization",
			"name" => $site_name,
			"url" => $site_url,
		],
	];
}

/**
 * GENERIC ENTITY METATAGS
 */
elseif ($entity instanceof ElggEntity) {
	if (!empty($entity->description)) {
		$meta_description = elgg_get_excerpt((string) $entity->description, 160);
	}
	$mainpage_image = $entity->getIconURL(['size' => 'large']);
	if (!empty($entity->tags)) {
		$tags = array_merge($tags, (array) $entity->tags);
	}

	// JSON-LD generic CreativeWork schema
	$jsonld = [
		"@context" => "https://schema.org",
		"@type" => "CreativeWork",
		"name" => $title,
		"description" => $meta_description,
		"url" => elgg_get_current_url(),
		"image" => $mainpage_image,
	];
}

// Add plugin keywords
$plugin_keywords = elgg_get_plugin_setting("mainpage_keywords", "metatags");
if ($plugin_keywords) {
	$tags[] = $plugin_keywords;
}
$tags = implode(",", array_filter($tags));

// Canonical URL
$current_url = elgg_get_current_url();

/**
 * OUTPUT META TAGS
 */
echo elgg_format_element('title', [], $title);

echo elgg_format_element('link', [
	'rel' => 'canonical',
	'href' => $current_url,
]);

echo elgg_format_element('meta', ['name' => 'author', 'content' => $author]);
echo elgg_format_element('meta', ['name' => 'keywords', 'content' => $tags]);
echo elgg_format_element('meta', ['name' => 'description', 'content' => $meta_description]);

// Open Graph
echo elgg_format_element('meta', ['property' => 'og:title', 'content' => $title]);
echo elgg_format_element('meta', ['property' => 'og:description', 'content' => $meta_description]);
echo elgg_format_element('meta', ['property' => 'og:type', 'content' => 'website']);
echo elgg_format_element('meta', ['property' => 'og:url', 'content' => $current_url]);
echo elgg_format_element('meta', ['property' => 'og:image', 'content' => $mainpage_image]);
echo elgg_format_element('meta', ['property' => 'og:site_name', 'content' => $site_name]);

// Twitter Cards
echo elgg_format_element('meta', ['name' => 'twitter:card', 'content' => 'summary_large_image']);
echo elgg_format_element('meta', ['name' => 'twitter:title', 'content' => $title]);
echo elgg_format_element('meta', ['name' => 'twitter:description', 'content' => $meta_description]);
echo elgg_format_element('meta', ['name' => 'twitter:url', 'content' => $current_url]);
echo elgg_format_element('meta', ['name' => 'twitter:image', 'content' => $mainpage_image]);

// Robots (optional)
echo elgg_format_element('meta', ['name' => 'robots', 'content' => 'index, follow']);

// JSON-LD Schema.org
if (!empty($jsonld)) {
	echo '<script type="application/ld+json">' . json_encode($jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
}

// Allow extensions via plugin hook
// $extra_meta = elgg_trigger_plugin_hook('meta_tags', 'head', $vars, []);
// if (is_array($extra_meta)) {
// 	foreach ($extra_meta as $tag) {
// 		echo $tag;
// 	}
// }