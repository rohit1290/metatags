<?php
namespace MetaTags;
use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

  public function init() {
    elgg_extend_view('page/elements/head', 'metatagsgen/metatags');

  	//Static caching of icons
    /*
  	if (elgg_get_plugin_setting("cloudflare", "metatags") == "yes") {
  		elgg_register_event_handler('entity:icon:url', 'user', 'metatags_user_icon_url_override');
  	}
    */
    
  	elgg_register_event_handler('view_vars', 'all', 'metatags_view_guid');
  }
}