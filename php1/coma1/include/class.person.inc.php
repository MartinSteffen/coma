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

  var $strEmail;
  var $strFirstName;
  var $strLastName;
  var $intRole;
  
  function Person($email, $firstname, $lastname, $role=0){
    $this->strEmail = $email;
    $this->strFirstName = $firstname;
    $this->strLastName = $lastname;
    $this->intRole = $role;
  }
  
  function hasRole($role){
    return ($role == $this->intRole);
  }
  
} // end class Person

?>