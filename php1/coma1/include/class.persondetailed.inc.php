<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('./class.person.inc.php');

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
  
  function PersonDetailed($intId, $email, $firstname, $lastname, $role, $title='', $affiliation='', $street='', $city='', $postalcode='', $state='', $country='', $phone='', $fax=''){
    $this->Person($intId, $email, $firstname, $lastname, $role);
    $this->strTitle = $title;
    $this->strAffiliation = $affiliation;
    $this->strStreet = $street;
    $this->strCity = $city;
    $this->strPostalCode = $postalcode;
    $this->strState = $state;
    $this->strCountry = $country;
    $this->strPhone = $phone;
    $this->strFax = $fax;
  }
  
  function PersonDetailedFromPerson($person, $title='', $affiliation='', $street='', $city='', $postalcode='', $state='', $country='', $phone='', $fax=''){
    $this->PersonDetailed($person->strEmail, $person->strFirstName, $person->strLastName, $person->intRole, $title, $affiliation, $street, $city, $postalcode, $state, $country, $phone, $fax);
  }

} // end class PersonDetailed

?>