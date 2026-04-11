<?php
namespace MetaTags;

class Events {
  
  public static function GetGUID(\Elgg\Event $event) {
    $return = $event->getValue();
    if(array_key_exists("guid", $return)) {
      set_input('meta_guid', (int) $return['guid']);
    }
    return $return;
  }
}
