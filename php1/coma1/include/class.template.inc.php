<?php
/**
 * @version $Id$
 * @package coma1
 */

if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse Template
 *
 * Klasse zum Parsen der Templates
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright &copy; 2004, Jan Waller
 * @package coma1
 * @subpackage Parser
 * 
 */
class Template {

  var $template='';
  var $errString = '';
  var $assocArray = array();

  function Template($filename) {
    return $this->readTemplate($filename);
  }

  function readTemplate($filename) {
    // vor PHP 4.3 waere es das gewesen:
    // $contents = implode("", @file($filename) );
    $contents = file_get_contents($filename);
    if (empty($contents)) {
      return $this->error("Could not read Template [$filename]");
    }
    $this->template = $contents;
    return true;
  }

  function assign($assocArray) {
    if(!is_array($assocArray)) {
      return $this->error('Not an Array');
    }
    $this->assocArray = array_merge($this->assocArray, $assocArray);
  }

  function parse() {
    $template = $this->template;
    $assocArray = $this->assocArray;
    $keyArray = array_keys($assocArray);
    $keyArray = array_map(create_function('$s', 'return "<{" . $s . "}>";'), $keyArray);
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

} // end class Template

?>