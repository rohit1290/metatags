<?php
/**
 * Elgg Metatags Plugin Settings (Elgg 6.3+)
 *
 * @author
 */

$plugin = elgg_extract('entity', $vars);

$noyes_options = [
    "no" => elgg_echo("option:no"),
    "yes" => elgg_echo("option:yes"),
];

$defaults = [
    'mainpage_title' => elgg_get_site_entity()->name . " - Social Network",
    'mainpage_description' => elgg_get_site_entity()->name . " is a social network for people with interest in ....",
    'mainpage_keywords' => "social network, join, register, members, " . elgg_get_site_entity()->name,
    'mainpage_image' => '',
    'cloudflare' => 'no',
];

// Merge plugin settings with defaults
$values = [];
foreach ($defaults as $key => $default) {
    $values[$key] = $plugin->$key ?? $default;
}

// Settings form
/*
echo elgg_view_field([
    '#type' => 'select',
    '#label' => elgg_echo('metatags:cloudflare'),
    '#help' => elgg_echo('Enable Cloudflare integration for faster caching and SEO.'),
    'name' => 'params[cloudflare]',
    'value' => $values['cloudflare'],
    'options_values' => $noyes_options,
]);
*/

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:title'),
    '#help' => elgg_echo('This title will be used as the default meta title for your site.'),
    'name' => 'params[mainpage_title]',
    'value' => $values['mainpage_title'],
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:description'),
    '#help' => elgg_echo('Short description of your site (max ~160 characters). Used for SEO and social media previews.'),
    'name' => 'params[mainpage_description]',
    'value' => $values['mainpage_description'],
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:keywords'),
    '#help' => elgg_echo('Comma-separated keywords relevant to your site.'),
    'name' => 'params[mainpage_keywords]',
    'value' => $values['mainpage_keywords'],
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:image'),
    '#help' => elgg_echo('Default image (1200x630 recommended) used for social sharing when no entity image is available.'),
    'name' => 'params[mainpage_image]',
    'value' => $values['mainpage_image'],
]);
