<?php

function meta_skip_pages($context) {
  $pages = [
    'admin',
    'changepassword',
  ];
  
  if (in_array($context, $pages, true)) {
    return true;
  }
  return false;
}
?>