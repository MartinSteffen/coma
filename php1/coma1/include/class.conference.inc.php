<?php
/**
 * $Id
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**
 * Klasse Conference
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author  Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Conference {

  var $intId;
  var $strName;
  var $strHomepage;
  var $strDescription;
  var $strStart;
  var $strEnd;

  function Conference($intId, $strName, $strHomepage, $strDescription, $strStart, $strEnd) {
    $this->intId = $intId;
    $this->strName = $strName;
    $this->strHomepage = $strHomepage;
    $this->strDescription = $strDescription;
    $this->$strStart = $strStart;
    $this->$strEnd = $strEnd;
  }

  function getDateString() {
    if (empty($this->strStart)) {
      return "not announced";
    }
    else if (!strcmp($this->strStart, $this->strEnd) || empty($this->strEnd)) {
      return encodeText($this->strStart);
    }    
    return encodeText($this->strStart." - ".$this->strEnd);    
  }

} // end class Conference

?>