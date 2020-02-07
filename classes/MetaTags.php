<?php
use Elgg\DefaultPluginBootstrap;

class MetaTags extends DefaultPluginBootstrap {

  public function init() {
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
}