<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('class.person.inc.php');

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

  var $strTitle;
  var $strAffiliation;
  var $strStreet;
  var $strCity;
  var $strPostalCode;
  var $strState;
  var $strCountry;
  var $strPhone;
  var $strFax;
  
  function PersonDetailed($intId, $strEmail, $strFirstname, $strLastname, $intRole = 0,
                          $strTitle = '', $strAffiliation = '', $strStreet = '',
                          $strCity = '', $strPostalCode = '', $strState = '',
                          $strCountry = '', $strPhone = '', $strFax = '') {
    $this->Person($intId, $strEmail, $strFirstname, $strLastname, $intRole);
    $this->strTitle = $strTitle;
    $this->strAffiliation = $strAffiliation;
    $this->strStreet = $strStreet;
    $this->strCity = $strCity;
    $this->strPostalCode = $strPostalCode;
    $this->strState = $strState;
    $this->strCountry = $strCountry;
    $this->strPhone = $strPhone;
    $this->strFax = $strFax;
  }
  
} // end class PersonDetailed

?>