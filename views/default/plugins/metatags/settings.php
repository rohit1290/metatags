<?php
echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:keywords'),
    '#help' => elgg_echo('metatags:mainpage:keywordshelp'),
    'name' => 'params[mainpage_keywords]',
    'value' => $vars['entity']->mainpage_keywords,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:image'),
    '#help' => elgg_echo('metatags:mainpage:imagehelp'),
    'name' => 'params[mainpage_image]',
    'value' => $vars['entity']->mainpage_image,
]);

echo elgg_view_field([
    '#type' => 'email',
    '#label' => elgg_echo('metatags:mainpage:email'),
    '#help' => elgg_echo('metatags:mainpage:emailhelp'),
    'name' => 'params[mainpage_email]',
    'value' => $vars['entity']->mainpage_email,
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:phone_number'),
    '#help' => elgg_echo('metatags:mainpage:phone_numberhelp'),
    'name' => 'params[mainpage_phone_number]',
    'value' => $vars['entity']->mainpage_phone_number,
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:fax_number'),
    '#help' => elgg_echo('metatags:mainpage:fax_numberhelp'),
    'name' => 'params[mainpage_fax_number]',
    'value' => $vars['entity']->mainpage_fax_number,
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:locality'),
    '#help' => elgg_echo('metatags:mainpage:localityhelp'),
    'name' => 'params[mainpage_locality]',
    'value' => $vars['entity']->mainpage_locality,
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:region'),
    '#help' => elgg_echo('metatags:mainpage:regionhelp'),
    'name' => 'params[mainpage_region]',
    'value' => $vars['entity']->mainpage_region,
]);

echo elgg_view_field([
    '#type' => 'number',
    '#label' => elgg_echo('metatags:mainpage:postal'),
    '#help' => elgg_echo('metatags:mainpage:postalhelp'),
    'name' => 'params[mainpage_postal]',
    'value' => $vars['entity']->mainpage_postal,
]);

echo elgg_view_field([
    '#type' => 'text',
    '#label' => elgg_echo('metatags:mainpage:country'),
    '#help' => elgg_echo('metatags:mainpage:countryhelp'),
    'name' => 'params[mainpage_country]',
    'value' => $vars['entity']->mainpage_country,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:facebook'),
    '#help' => elgg_echo('metatags:mainpage:facebookhelp'),
    'name' => 'params[facebook]',
    'value' => $vars['entity']->facebook,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:twitter'),
    '#help' => elgg_echo('metatags:mainpage:twitterhelp'),
    'name' => 'params[twitter]',
    'value' => $vars['entity']->twitter,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:linkedin'),
    '#help' => elgg_echo('metatags:mainpage:linkedinhelp'),
    'name' => 'params[linkedin]',
    'value' => $vars['entity']->linkedin,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:instagram'),
    '#help' => elgg_echo('metatags:mainpage:instagramhelp'),
    'name' => 'params[instagram]',
    'value' => $vars['entity']->instagram,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:youtube'),
    '#help' => elgg_echo('metatags:mainpage:youtubehelp'),
    'name' => 'params[youtube]',
    'value' => $vars['entity']->youtube,
]);

echo elgg_view_field([
    '#type' => 'url',
    '#label' => elgg_echo('metatags:mainpage:pintrest'),
    '#help' => elgg_echo('metatags:mainpage:pintresthelp'),
    'name' => 'params[pintrest]',
    'value' => $vars['entity']->pintrest,
]);
