<?php
$context = elgg_get_context();
if($context == "admin") {	return; }

$jsonld = [];

$meta_guid = get_input('meta_guid');
$entity = null;
if($meta_guid > 0) {
	$entity = get_entity($meta_guid);
}
$page_owner = elgg_get_page_owner_entity();
if($page_owner == null && $entity != null) {
	$page_owner_guid = $entity->container_guid ?: $entity->owner_guid;
	$page_owner = get_entity($page_owner_guid);
}
if($page_owner == null) {
	return;
}
$url = elgg_get_current_url();

$meta = new \MetaTags\MetaManager();
$meta->setURL($url);
$meta->set('og:type', $context);

$title = elgg_extract('title', $vars);
$title_flag = 0;
if(!empty($title)) {
	$meta->setTitle($title);
	$title_flag = 1;
}

$jsonld[] = [
	"@context" => "https://schema.org",
	"@type" => "Organization",
	"name" => $meta->getSite('name'),
	"description" => $meta->getSite('description'),
	"url" => $meta->getSite('url'),
	"logo" => $meta->getSite('image'),
	'email' => $meta->getSite('email'),
	'telephone' => $meta->getSite('phone_number'),
	"address" => [
    "@type" => "PostalAddress",
    "addressLocality" => $meta->getSite('locality'),
    "addressRegion" => $meta->getSite('region'),
    "postalCode" => $meta->getSite('postal'),
    "addressCountry" => $meta->getSite('country'),
  ],
	"sameAs" => [
		$meta->getSite('facebook'),
		$meta->getSite('twitter'),
		$meta->getSite('linkedin'),
		$meta->getSite('instagram'),
		$meta->getSite('youtube'),
		$meta->getSite('pintrest'),
	],
];

if ($entity instanceof ElggEntity) {
	// var_dump("Metatags for ElggObject - $context");
	if($title_flag == 0) {
		$title = $entity->getDisplayName() . " : " . $meta->getSite('name');
		$meta->setTitle($title);
	}
	$meta->set('owner', $page_owner->getDisplayName());
	if (!empty($entity->briefdescription)) {
		$meta->setDescription(elgg_get_excerpt((string) $entity->briefdescription, 160));
	} elseif (!empty($entity->description)) {
		$meta->setDescription(elgg_get_excerpt((string) $entity->description, 160));
	}
	$meta->setImage($page_owner->getIconURL(['size' => 'large']));
	$meta->addKeyword($entity->getDisplayName());
	if (!empty($entity->tags)) {
		$meta->addKeyword($entity->tags);
	}
	
	
	$meta->set('revised',  date('Y-m-d', $entity->time_updated));
	$meta->set('topic',  $entity->getDisplayName());
	if($entity->getOwnerEntity() instanceof ElggUser) {
		$meta->set('author', $entity->getOwnerEntity()->getDisplayName());
	}
	if (!empty($entity->briefdescription)) {
		$meta->set('summary', elgg_get_excerpt((string) $entity->briefdescription, 160));
	} elseif (!empty($entity->description)) {
		$meta->set('summary', elgg_get_excerpt((string) $entity->description, 160));
	}
	
	$tmp = [
		"@context" => "https://schema.org",
		"name" => $meta->get('topic'),
		"description" => $meta->get('summary'),
		"url" => $meta->get('og:url'),
		"image" => $meta->get('og:image'),
	];
	
	switch ($entity->getSubtype()) {
		case 'events':
			$tmp = array_merge($tmp, [
				"@type" => "Event",
			  "startDate" => date('Y-m-d', $entity->event_start),
			  "endDate" => date('Y-m-d',  $entity->event_end),
			  "location" => [
			    "@type" => "Place",
			    "name" => $entity->region,
			    "address" => [
			      "@type" => "PostalAddress",
			      "streetAddress" => $entity->location,
			    ]
			  ],
			  "Organization" => [
			    "@type" => "EventOrganizer",
			    "name" => $entity->organizer
			  ]
			]);
			break;
		
		case 'blog':
			$tmp = array_merge($tmp, [
				"@type" => "BlogPosting",
				"headline" => $meta->get('topic'),
				"editor" => $meta->get('author'),
				"keywords" => $meta->get('tags'),
				"wordcount" => "1120",
				"publisher" => $meta->get('owner'),
				"datePublished" => date('Y-m-d', $entity->time_created),
				"dateCreated" => date('Y-m-d', $entity->time_created),
				"dateModified" => date('Y-m-d', $entity->time_updated),
				"articleBody" => trim($entity->description),
				"author" => [
					"@type" => "Person",
					"name" => $meta->get('author'),
				]
			]);
			break;

		case 'file':
			$tmp = array_merge($tmp, [
				"@type" => "MediaObject",
				"contentUrl" => $entity->getDownloadURL(),
				"encodingFormat" => $entity->getMimeType(),
				"fileSize" => $entity->getSize(),
				"uploadDate" => date('Y-m-d', $entity->getModifiedTime()),
			]);
			break;
		
		default:
			break;
	}
	
	$jsonld[] = $tmp;
	

} elseif ($page_owner instanceof ElggUser) {
	// var_dump("Metatags for user pages (settings, profile, messages etc) - $context");
	if($title_flag == 0) {
		$title = $page_owner->getDisplayName() . " : " . $meta->getSite('name');
		$meta->setTitle($title);
	}
	$meta->set('owner', $page_owner->getDisplayName());
	
	if (!empty($page_owner->briefdescription)) {
		$meta->setDescription(elgg_get_excerpt((string) $page_owner->briefdescription, 160));
	} elseif (!empty($page_owner->description)) {
		$meta->setDescription(elgg_get_excerpt((string) $page_owner->description, 160));
	}
	$meta->setImage($page_owner->getIconURL(['size' => 'large']));
	$meta->addKeyword($page_owner->getDisplayName());
	if (!empty($page_owner->skills)) {
		$meta->addKeyword($page_owner->skills);
	}
	if (!empty($page_owner->interests)) {
		$meta->addKeyword($page_owner->interests);
	}
	if (!empty($page_owner->location)) {
		$meta->addKeyword($page_owner->location);
	}
	
	$jsonld[] = [
		"@context" => "https://schema.org",
		"@type" => "Person",
		"name" => $meta->get('og:title'),
		"description" => $meta->get('og:description'),
		"url" => $meta->get('og:url'),
		"image" => $meta->get('og:image'),
		"memberOf" => [
			"@type" => "Organization",
			"name" => $meta->getSite('name'),
			"url" => $meta->getSite('url'),
		]
	];
} elseif ($page_owner instanceof ElggGroup) {
	// var_dump("Metatags for generic pages (inside group) - $context");
	if($title_flag == 0) {
		$title = $page_owner->getDisplayName() . " : " . $meta->getSite('name');
		$meta->setTitle($title);
	}
	if($page_owner->getOwnerEntity() instanceof ElggUser) {
		$meta->set('owner', $page_owner->getOwnerEntity()->getDisplayName());
	}
	if (!empty($page_owner->description)) {
		$meta->setDescription(elgg_get_excerpt((string) $page_owner->description, 160));
	}
	$meta->setImage($page_owner->getIconURL(['size' => 'large']));
	$meta->addKeyword($page_owner->getDisplayName());
	if (!empty($page_owner->tags)) {
		$meta->addKeyword($page_owner->tags);
	}
	
	$jsonld[] = [
		"@context" => "https://schema.org",
		"@type" => "Organization",
		"name" => $meta->get('og:title'),
		"description" => $meta->get('og:description'),
		"url" => $meta->get('og:url'),
		"image" => $meta->get('og:image'),
		"parentOrganization" => [
			"@type" => "Organization",
			"name" => $meta->getSite('name'),
			"url" => $meta->getSite('url'),
		],
	];
	
} else {
	// var_dump("Metatags for Generic (not matching any condition above) - $context");
	// TBD: Don't think we would have any such situation. If there is any the default meta would be shown.
	// To be removed in the future.
}

$data = array_filter($meta->getAll(), function($value) {
	return $value !== '';
});

/*****************************************
  Output the metatags
*****************************************/
// Site Title
echo elgg_format_element('title', [], $title);
// Site Link
echo elgg_format_element('link', ['rel' => 'canonical',	'href' => $url]);
// Site Meta
foreach ($data as $key => $value) {
	echo elgg_format_element('meta', ['name' => $key, 'content' => $value]);
}

// Print the data for debuggin
// echo "<pre>";
// print_r($data);
// echo "</pre>";


// Site JSON-LD
if (!empty($jsonld)) {
	foreach ($jsonld as $json) {
		// Remove empty values recursively
		$filtered = array_filter($json, function ($value) {
			if (is_array($value)) {
				$value = array_filter($value, function ($v) {
					return !empty($v);
				});
				return !empty($value);
			}
			return !empty($value);
		});
		echo elgg_format_element('script', [
			'type' => 'application/ld+json',
		], json_encode(
			$filtered,
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
		));
		
		// Print the jsonld for debuggin
		// echo "<pre>";
		// print_r($filtered);
		// echo "</pre>";
	}
}
