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

  var template_ = '';
  var errString_ = '';

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
    $this->template_ = $contents;
    return true;
  }
  
  function parse($assocArray) {
    $template = $this->template_;
    $keyArray = array_keys($assocArray);
    array_map(create_function('&$s', 'return "{$s}";'), $keyArray);
    $template = preg_replace($keyArray, array_values($assocArray), $template);
    return $template;
  }
  
  function error($text) {
    $this->errString_ = $text;
    return false;
  }
  
  function getLastError() {
    $errString = $this->errString_;
    $this->errString_ = '';
    return $errString;
  }

} // End Class

?>