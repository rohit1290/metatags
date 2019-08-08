<?php
/**
 * Elgg Metatags generator plugin
 * This plugin make the metatags for content.
 *
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Gerard Kanters
 * Website: https://www.centillien.com
 */

require_once __DIR__ . '/lib/functions.php';

elgg_register_event_handler('init', 'system', 'metatagsgen_init');

function metatagsgen_init() {

	elgg_extend_view('page/elements/head', 'metatagsgen/metatags');

	//Static caching of icons
	$cloudflare = elgg_get_plugin_setting("cloudflare", "metatags");
	if ($cloudflare == "yes") {
		elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'metatags_user_icon_url_override');
	}

	//Unregister systemlog since it is not very usefull
	elgg_unregister_event_handler('log', 'systemlog', 'system_log_default_logger');
	elgg_register_plugin_hook_handler('view_vars', 'all', 'metatags_view_guid');
}

function metatags_user_icon_url_override(\Elgg\Hook $hook) {
	$user = $hook->getParam('entity');
	$size = $hook->getParam('size');

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

function metatags_view_guid(\Elgg\Hook $hook) {
	$return_value = $hook->getValue();
	if (isset($return_value['guid']) && get_input('guid', false) === false) {
		set_input('guid', $return_value['guid']);
	}
	return $return_value;
}
