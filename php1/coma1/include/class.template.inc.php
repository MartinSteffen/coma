<?php

/**
 * Klasse zum Parsen der Templates
 *
 * $Id$
 *
 */

if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

class Template {

  var template = '';
  var errString = '';

  function Template() {
    return true;
  }
  
  function readTemplate($template) {
    // vor PHP 4.3 waere es das gewesen:
    // $contents = implode("", @file($template) );
    $contents = file_get_contents($template)
    if (empty($contents)) {
      return this->error("Could not read Template [$template]");
    }
    $this->template = $contents;
    return true;
  }
  
  function parse($assocArray) {
    $template = $this->template;
    $keyArray = array_keys($assocArray);
    array_map(create_function('&$s', 'return "{$s}";'), $keyArray);
    $template = preg_replace($keyArray, array_values($assocArray), $template);
    return $template;
  }
  
  function error($text) {
    $this->errString = $text;
    return false;
  }
  
  function getLastError() {
    $errString = $this->errString;
    $this->errString = '';
    return $errString;
  }

} // End Class

?>