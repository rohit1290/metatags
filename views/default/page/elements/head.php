<?php
/**
 * The HTML head
 *
 * @internal It's dangerous to alter this view.
 *
 * @uses $vars['title'] The page title
 * @uses $vars['metas'] Array of meta elements
 * @uses $vars['links'] Array of links
 */

/*
6/15/2026: Copy of "head.php"
Commit: https://github.com/Elgg/Elgg/commit/6659eb162f66bfc39d8f6ad1a3794811ee27d323
Changes:
1. Only show title for pages like admin, changepassword etc. (Page defined at meta_skipp_pages())
2. Removed "description" - Not needed for admin, changepassword pages.
*/

// 5/30: Removed below line:
if(meta_skip_pages(elgg_get_context())) {
  echo elgg_format_element('title', [], (string) elgg_extract('title', $vars), ['encode_text' => true]);
}

$metas = elgg_extract('metas', $vars, []);
foreach ($metas as $attributes) {
  // 5/30: Added below line:
  if (($attributes['name'] ?? '') === 'description') { continue; }
	echo elgg_format_element('meta', $attributes);
}

$links = elgg_extract('links', $vars, []);
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

echo elgg_view('page/elements/importmap.json');

$js_foot = elgg_get_loaded_external_resources('js', 'footer');
foreach ($js_foot as $resource) {
	$options = [
		'rel' => 'preload',
		'as' => 'script',
		'href' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('link', $options);
}

$stylesheets = elgg_get_loaded_external_resources('css', 'head');
foreach ($stylesheets as $name => $resource) {
	$options = [
		'data-name' => $name,
		'rel' => 'stylesheet',
		'href' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('link', $options);
}

$js_head = elgg_get_loaded_external_resources('js', 'head');
foreach ($js_head as $resource) {
	$options = [
		'src' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('script', $options);
}

// See https://github.com/Elgg/Elgg/issues/8328
echo elgg_format_element('script', [], '// A non-empty script otherwise Firefox will exhibit FOUC');

$imports = _elgg_services()->esm->getImports();
if (empty($imports)) {
	return;
}

?>
<script type="module">
<?php
foreach ($imports as $module) {
	echo "import '{$module}';" . PHP_EOL;
}
?>
</script>