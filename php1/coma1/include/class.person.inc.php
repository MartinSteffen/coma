<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse Person
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @author  Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Person {

  var $intId;
  var $strEmail;
  var $strFirstName;
  var $strLastName;
  var $intRoles;
  
  function Person($intId, $strEmail, $strFirstName, $strLastName, $intRoles = 0) {
    $this->intId = $intId;
    $this->strEmail = $strEmail;
    $this->strFirstName = $strFirstName;
    $this->strLastName = $strLastName;
    $this->intRoles = $intRoles;
  }
  
  function hasRole($intRole) {
    if ($this->intRoles & (1 << $intRole) ~= 0) {
      return true;
    }
    return false;
  }
  
} // end class Person

?>