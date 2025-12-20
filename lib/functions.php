<?php
/**
* Smartly trim a string without cutting sentences mid-way
*
* @param string $string
* @param int    $max_words
*
* @return string
*/
function smart_trim(string $string, int $max_words = 30): string {
	$words = preg_split('/\s+/', strip_tags($string));
	$count = count($words);
	
	if ($count <= $max_words) {
		return trim($string);
	}
	
	$excerpt = array_slice($words, 0, $max_words);
	$last_word = end($excerpt);
	
	// If the last word ends with punctuation, keep it
	if (preg_match('/[.!?]$/', $last_word)) {
		return implode(' ', $excerpt);
	}
	
	// Otherwise, keep adding until sentence ends or limit reached
	for ($i = $max_words; $i < $count; $i++) {
		$excerpt[] = $words[$i];
		if (preg_match('/[.!?]$/', $words[$i])) {
			break;
		}
	}
	
	return implode(' ', $excerpt);
}

/**
* Override user icon URL with external photo if available
*/
/*
function metatags_user_icon_url_override(\Elgg\Event $event) {
	$entity = $event->getParam('entity');
	$size = $event->getParam('size', 'medium');
	
	if (!$entity instanceof \ElggUser) {
		return null;
	}
	
	// Use external photo if set
	if (!empty($entity->externalPhoto)) {
		return $entity->externalPhoto;
	}
	
	// Otherwise fallback to default Elgg behavior
	return elgg_generate_entity_icon_url($entity, $size);
}
*/

/**
* Ensure 'guid' input is available in views where needed
*/
function metatags_view_guid(\Elgg\Event $event) {
	$return_value = $event->getValue();
	
	if (!empty($return_value['guid']) && !get_input('guid')) {
		elgg_set_input('guid', (int) $return_value['guid']);
	}
	
	return $return_value;
}

