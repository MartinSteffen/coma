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
 * Klasse DBAccess
 *
 * @author Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage DBAccess
 * @access protected
 */
class DBAccess {
  /**#@+
   * @access private
   */
  /**
   * @var MySql
   */
  var $mySql;
  /**
   * @var string
   */
  var $strError = '';
  /**#@-*/

  /**
   * Konstruktor
   *
   * Der Konstruktor erzeugt eine Verbindung mit der Datenbank.
   *
   * @param MySql $mySql Ein MySql-Objekt
   * @return bool <b>true</b> bei Erfolg, <b>false</b> bei Fehler
   * @see error()
   * @see getLastError()
   */
  function DBAccess(&$mySql) {
    $this->mySql = &$mySql;
    return true;
  }

  /**
   * Fehlerbeschreibung festlegen
   *
   * @param string $text Optionale Fehlerbeschreibung.
   * @return false Es wird immer <b>false</b> zurueckgegeben.
   * @see getLastError()
   * @access protected
   */
  function error($strError='') {
    $this->strError = "[DBAccess: $strError ]";
    return false;
  }

  /**
   * Letzten Fehler zurueckgeben
   *
   * Die Funktion <b>getLastError()</b> gibt die letzte mit error
   * gesicherte Fehlermeldung zurueck und loescht diese aus dem Speicher.
   *
   * @return string Die letzte Fehlermeldung.
   * @see error()
   * @access public
   */
  function getLastError() {
    $strError = $this->strError;
    $this->strError = '';
    return $strError;
  }


  /**
   */
  function getAllConferences() {
    return true;
  }

  /**
   */
  function checkLogin() {
    return true;
  }

  /**
   */
  function getConferenceConfig() {
    return true;
  }

  /**
   */
  function getPersonIdByEmail($strEmail) {
    $s = 'SELECT  id'.
        ' FROM    Person'.
        ' WHERE   email = \''.$strEmail.'\'';
    $data = $this->mySql->select($s);
    if ($data) {
      return $data[0]['id'];
    }
    return false;
  }
  
  /**
   */
  function getPerson($intPersonId) {
    $s = 'SELECT  id, email, first_name, last_name'.
        ' FROM    Person'.
        ' WHERE   id = '.$intPersonId;
    $data = $this->mySql->select($s);
    if ($data) {
      $s = 'SELECT  role_type'.
          ' FROM    Role'.
          ' WHERE   person_id = '.$data[0]['id'];
      $role_data = $this->mySql->select($s);
      $role_type = 0;
      if ($role_data) {
      	for ($i = 0; $i < count($role_data); $i++) {
      	  $role_type = $role_type | (1 << $role_data[$i]['role_type']);
      	}
      }
      return (new Person($data[0]['id'], $data[0]['email'],
                $data[0]['first_name'], $data[0]['last_name'], $role_type));
    }
    return false;
  }

  /**
   */
  function getPersonDetailed($intPersonId) {
    $s = 'SELECT  id, email, first_name, last_name, title, affiliation,'.
        '         street, city, postal_code, state, country, phone, fax'.
        ' FROM    Person'.
        ' WHERE   id = '.$intPersonId;
    $data = $this->mySql->select($s);
    if ($data) {
      $s = 'SELECT  role_type'.
          ' FROM    Role'.
          ' WHERE   person_id = '.$data[0]['id'];
      $role_data = $this->mySql->select($s);
      $role_type = 0;
      if ($role_data) {
      	for ($i = 0; $i < count($role_data); $i++) {
      	  $role_type = $role_type | (1 << $role_data[$i]['role_type']);
      	}
      }
      return (new PersonDetailed($data[0]['id'], $data[0]['email'],
                $data[0]['first_name'], $data[0]['last_name'], $role_type,
                $data[0]['title'], $data[0]['affiliation'], $data[0]['street'],
                $data[0]['city'], $data[0]['postal_code'], $data[0]['state'],
                $data[0]['country'], $data[0]['phone'], $data[0]['fax']));
    }
    echo('Else');
    return false;
  }

  /**
   */
  function getPapersOfAuthor($intAuthorId) {
    return true;
  }

  /**
   */
  function getPapersOfReviewer($intReviewerId) {
    return true;
  }

  /**
   */
  function getPaperDetailed($intPaperId) {
    return true;
  }

  /**
   */
  function getReviewsOfReviewer($intReviewerId) {
    return true;
  }

  /**
   */
  function getReviewersOfPaper($intPaperId) {
    return true;
  }

  /**
   */
  function getReviewsOfPaper($intPaperId) {
    return true;
  }

  /**
   */
  function getReviewDetailed($intReviewId) {
    return true;
  }

  /**
   */
  function checkAccessToForum($intForumId) {
    return true;
  }

  /**
   */
  function getAllForums() {
    return true;
  }

  /**
   */
  function getForumOfPaper($intPaperId) {
    return true;
  }

  /**
   */
  function getForumsOfUser($strUserEmail) {
    return true;
  }

  /**
   */
  function getForumDetailed($intForumId) {
    return true;
  }

}

?>