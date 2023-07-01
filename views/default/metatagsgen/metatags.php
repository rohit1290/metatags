<?php
/**
 * Elgg Metatags generator plugin
 * This plugin make the metatags for content.
 *
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Gerard Kanters
 * Website: https://www.centillien.com
 */
$title = elgg_extract('title', $vars);
$user = elgg_get_page_owner_entity();
$mainpage_image = elgg_get_plugin_setting("mainpage_image", "metatags");
$guid = elgg_extract('guid', $vars, null);
$cguid = elgg_extract('container_guid', $vars, null);
if ($cguid != null) {
	$entity = get_entity($cguid);
} else if ($guid != null) {
	$entity = get_entity($guid);
} else {
	$entity = elgg_extract('entity', $vars, null);
}
$site_name = elgg_get_site_entity()->name;

// Title
if ($title == "") {
	$title = $site_name;
}

// Description
$meta_description = elgg_get_plugin_setting("mainpage_description", "metatags");
if ($entity != null) {
	$meta_description = elgg_get_excerpt($entity->description);
}
// Author
if (empty($user->name)) {
	$author = $site_name;
} else {
	$author = $user->name;
}

// Image
if ($entity != null) {
	$mainpage_image = $entity->getIconURL('large');
} else if (!empty($user->name)) {
	$mainpage_image = $user->getIconURL('large');
}

// keywords
$tags = elgg_get_context();
if ($entity !== null) {
	$tags .= ",".implode(",", $entity->tags);
}
if($user != null) {
	$tags .= "," . $user->name . ",". $user->location;
}
$tags .= ",".elgg_get_plugin_setting("mainpage_keywords", "metatags");
$tags = implode(",", array_filter(explode(",", $tags)));
?>
<title><?php echo $title; ?></title>
<link rel="author" href="<?php echo $author ?>"/>
<meta name="author" content="<?php echo $author ?>"/>
<meta name="keywords" content="<?php echo $tags ?>"/>

<meta property="og:title" content="<?php echo $title ?>"/>
<meta property="og:description" content="<?php echo $meta_description ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="<?php echo elgg_get_current_url(); ?>"/>
<meta property="og:image" content="<?php echo $mainpage_image ?>"/>
<meta property="og:site_name" content="<?php echo $site_name ?>"/>

<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?php echo $title ?>" />
<meta name="twitter:description" content="<?php echo $meta_description?>" />
<meta name="twitter:url" content="<?php echo elgg_get_current_url(); ?>" />
<meta name="twitter:image" content="<?php echo $mainpage_image ?>" />
