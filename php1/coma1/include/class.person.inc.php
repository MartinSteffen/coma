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
  var $strFirstName;
  var $strLastName;
  var $strEmail;
  var $intRoles;
  var $strTitle;
  
  function Person($intId, $strFirstName, $strLastName, $strEmail, $intRoles, $strTitle) {
    $this->intId = $intId;  
    $this->strFirstName = $strFirstName;
    $this->strLastName = $strLastName;
    $this->strEmail = $strEmail;
    $this->intRoles = $intRoles;
    $this->strTitle = $strTitle;
  }
  
  function hasRole($intRole) {
    return (($this->intRoles & (1 << $intRole)) != 0);
  }

  function hasAnyRole($intRole) {
    return ($this->intRoles != 0);
  }
  
  /**
   * Liefert den vollstaendigen Namen der Person wahlweise in der Form
   * "Vorname Nachname" ($intStyle = 0) oder "Nachname, Vorname" ($intStyle = 1).
   *
   * @param int $intStyle (optional) (0 oder 1) Art der Ausgabe
   * @return Name der Person
   * @access public
   * @author Tom (04.12.04)
   */
  function getName($intStyle = 0) {
    // erstmal ganz billig; spaeter Pruefung auf leere Strings usw.
    switch($intStyle) {
      case 0:
        $ret = $this->strFirstName.' '.$this->strLastName;
        break;
      case 1:
        $ret = $this->strLastName.', '.$this->strFirstName;
        break;
      default:
        $ret = 'Ungueltiger Parameterwert fuer $intStyle in Person.getName().';
    }
    return $ret;
  }
  
} // end class Person

?>