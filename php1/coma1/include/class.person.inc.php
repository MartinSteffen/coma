<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
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
  var $intRequestedRoles;
  var $strTitle;

  function Person($intId, $strFirstName, $strLastName, $strEmail, $intRoles, $strTitle) {
    $this->intId = $intId;
    $this->strFirstName = $strFirstName;
    $this->strLastName = $strLastName;
    $this->strEmail = $strEmail;
    $this->intRoles = $intRoles;
    $this->strTitle = $strTitle;
    $this->intRequestedRoles = 0;
  }

  /**
   * Fuegt der Person die Rolle $intRole hinzu.
   *
   * Es findet keine Aenderung in der Datenbank statt, lediglich im Objekt!
   *
   * @param int $intRole Ein Rollen-Enum (keine Abfrage auf Gueltigkeit!)
   * @param bool $blnNotAccepted false gdw. die Rolle bereits akzeptiert wurde
   * @return bool konstant: true
   * @access public
   * @author Tom (04.12.04)
   */
  function addRole($intRole, $blnNotAccepted=false) {
    if (empty($blnNotAccepted)) {
      $this->intRoles |= (1 << $intRole);
    }
    else {
      $this->intRequestedRoles |= (1 << $intRole);
    }
    return true;
  }

  /**
   * Aendert die Rolle $intRole der Person ().
   *
   * Es findet keine Aenderung in der Datenbank statt, lediglich im Objekt!
   *
   * @param int $intRole Ein Rollen-Enum (keine Abfrage auf Gueltigkeit!)
   * @return bool konstant: true
   * @access public
   * @author Tom (04.12.04)
   */
  function switchRole($intRole) {
    $this->intRoles = ~((~(1 << $intRole)) ^ ($this->intRoles));
    return true;
  }

  /**
   * Aendert die Rolle $intRole der Person ().
   *
   * Es findet keine Aenderung in der Datenbank statt, lediglich im Objekt!
   *
   * @param int $intRole Ein Rollen-Enum (keine Abfrage auf Gueltigkeit!)
   * @return bool konstant: true
   * @access public
   * @author Tom (04.12.04)
   */
  function deleteRole($intRole) {
    if ($this->hasRole($intRole)) {
      $this->switchRole($intRole);
    }
    return true;
  }

  function hasRole($intRole) {
    return ($this->intRoles & (1 << $intRole));
  }

  function hasAnyRole() {
    return ($this->intRoles != 0);
  }

  function hasRequestedRole($intRole) {
    return ($this->intRequestedRoles & (1 << $intRole));
  }

  function hasAnyRoleRequest() {
    return ($this->intRequestedRoles != 0);
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
        $ret = (empty($this->strFirstName)?'':$this->strFirstName.' ').$this->strLastName;
        break;
      case 1:
        $ret = $this->strLastName.(empty($this->strFirstName)?'':', '.$this->strFirstName);
        break;
      case 2:
        $ret = "$this->strTitle $this->strFirstName $this->strLastName";
        break;
      default:
        $ret = 'Invalid value for parameter $intStyle in Person.getName().';
    }
    return trim($ret);
  }

} // end class Person

?>