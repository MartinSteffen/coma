<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}
require_once(INCPATH.'class.person.inc.php');

/**
 * Klasse PersonDetailed
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @author  Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PersonDetailed extends Person {

  var $strAffiliation;
  var $strStreet;
  var $strCity;
  var $strPostalCode;
  var $strState;
  var $strCountry;
  var $strPhone;
  var $strFax;

  function PersonDetailed($intId, $strFirstname, $strLastname, $strEmail, $intRole,
                          $strTitle, $strAffiliation, $strStreet,
                          $strCity, $strPostalCode, $strState,
                          $strCountry, $strPhone, $strFax) {
    $this->Person($intId, $strFirstname, $strLastname, $strEmail, $intRole, $strTitle);
    $this->strAffiliation = $strAffiliation;
    $this->strStreet = $strStreet;
    $this->strCity = $strCity;
    $this->strPostalCode = $strPostalCode;
    $this->strState = $strState;
    $this->strCountry = $strCountry;
    $this->strPhone = $strPhone;
    $this->strFax = $strFax;
  }

  function getAddress() {
    $strAddress = $this->strStreet;
    if ( !empty($this->strStreet) &&
        (!empty($this->strPostalCode) || !empty($this->strCity))) {
      $strAddress .= ', ';
    }
    $strAddress .= $this->strPostalCode;
    if (!empty($this->strPostalCode) && !empty($this->strCity)) {
      $strAddress .= ' ';
    }
    $strAddress .= $this->strCity;    
    return $strAddress;
  }
  
  function getCountry() {
    $strAddress = $this->strState;
    if (!empty($this->strState) && !empty($this->strCountry)) {
      $strAddress .= ' ';
    }
    $strAddress .= $this->strCountry;    
    return $strAddress;
  }

} // end class PersonDetailed

?>