<?php
//Function to help make clean descriptions
//This function intelligently trims a body of text to a certain
//number of words, but will not break a sentence.
function smart_trim($string, $truncation) {
	$matches = preg_split("/\s+/", $string);
	$count = count($matches);

	if ($count > $truncation) {
		//Grab the last word; we need to determine if
		//it is the end of the sentence or not
		$last_word = strip_tags($matches[$truncation-1]);
		$lw_count = strlen($last_word);

		//The last word in our truncation has a sentence ender
		if ($last_word[$lw_count-1] == "." || $last_word[$lw_count-1] == "?" || $last_word[$lw_count-1] == "!") {
			for ($i=$truncation; $i<$count; $i++) {
				unset($matches[$i]);
			}

		//The last word in our truncation doesn't have a sentence ender, find the next one
		} else {
			//Check each word following the last word until
			//we determine a sentence's ending
			for ($i=($truncation); $i<$count; $i++) {
				if ($ending_found != true) {
					$len = strlen(strip_tags($matches[$i]));
					if ($matches[$i][$len-1] == "." || $matches[$i][$len-1] == "?" || $matches[$i][$len-1] == "!") {
						//Test to see if the next word starts with a capital
						if ($matches[$i+1][0] == strtoupper($matches[$i+1][0])) {
							$ending_found = true;
						}
					}
				} else {
					unset($matches[$i]);
				}
			}
		}

		//Check to make sure we still have a closing <p> tag at the end
		$body = implode(' ', $matches);
		if (substr($body, -4) != "</p>") {
			$body = $body."</p>";
		}

		return $body;
	} else {
		return $string;
	}
}

function metatags_user_icon_url_override(\Elgg\Event $event) {
	$user = $event->getParam('entity');
	$size = $event->getParam('size');

	if (isset($user->externalPhoto)) {
		// return thumbnail
		return $user->externalPhoto;
	} else {
		if (isset($user->icontime)) {
			return "avatar/view/$user->username/$size/$user->icontime.jpg";
		} else {
			return "_graphics/icons/user/default{$size}.gif";
		}
	}
}

function metatags_view_guid(\Elgg\Event $event) {
	$return_value = $event->getValue();
	if (isset($return_value['guid']) && get_input('guid', false) === false) {
		set_input('guid', $return_value['guid']);
	}
	return $return_value;
}

