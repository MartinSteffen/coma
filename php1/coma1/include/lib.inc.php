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

/**
 * Fehler auf hoechster Ebene abfangen und ausgeben
 *
 * @param string $strMethod Methode in der der Fehler aufgetreten ist
 * @param string $strError Beschreibung des Fehlers
 * @param string $strComment optionaler weiterer Kommentar
 */
function error($strMethod, $strError, $strComment='') {
  $strComment = empty($strComment) ? '' : " ($strComment)";
  $strError = '['.basename($_SERVER['PHP_SELF'],'.php')."->$strMethod: $strError$strComment]";
  include(TPLPATH.'error.php');
  echo($strError);
  die(1);
}

/**
 * Weiterleitung auf ein anderes Skript
 *
 * Diese Funktion lenkt den Benutzer auf einen anderen Skript weiter, in
 * dem die Bearbeitung fortgef�hrt wird. Dabei wird sichergestellt, das
 * Sessioninformationen erhalten bleiben.
 *
 * @warning nur vor irgendwelchen Ausgaben aufrufen!
 * @param string $strName Das aufzurufende Skript.
 */
function redirect($strName) {
  global $mySession;
  session_write_close();
  header('Location:' . COREURL . $strName . $mySession->getUrlId('?'));
  die(0);
}

/**
 * Erzeugen von Standard-Zuweisungen fuer Templates
 *
 * Die Funktion gibt ein Array mit Standard-Zuweisungen fuer
 * Templates zurueck!
 *
 * @return array Das geforderte Array
 */
function defaultAssocArray() {
  global $mySession;
  return array(
               'path'      => TPLURL,
               'basepath'  => COREURL,
               'filename'  => basename($_SERVER['PHP_SELF'],'.php'),
               '?SID'       => $mySession->getUrlId('?'),
               '&SID'       => $mySession->getUrlId('&')
              );
}

/**
 * This function encodes the string.
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function encodeText($_str) {
  return htmlentities($_str, ENT_QUOTES, 'UTF-8');
}

/**
 * This function makes an encoded String URL valid (gives an valid Link!)
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function encodeURL($_str) {
  $_str = decodeText($_str);
  $_str = str_replace('\'', urlencode('\''), $_str);
  $_str = str_replace('"', urlencode('"'), $_str);
  if (!preg_match('#^http[s]?:\/\/#i', $_str)) {
    $_str = 'http://' . $_str;
  }
  if (!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $_str))
  {
    $_str = '';
  }
  return $_str;
}

/**
 * Diese Funktion ueberprueft ob der aktuelle Benutzer korrekt eingeloggt ist.
 *
 * @return bool True oder False
 */
function checkLogin() {
  global $myDBAccess;
  if ($myDBAccess->checkLogin(session('uname',false), session('password', false))) {
    return true;
  }
  else {
    if ($myDBAccess->failed()) {
      error('checkLogin',$myDBAccess->getLastError());
    }
    return false;
  }
}

/**
 * geschuetztes Lesen von Session-Variablen
 *
 * Diese Funktion liest eine Sessionvariable aus, und stellt dabei sicher
 * das diese auch gesetzt ist! Im Fehlerfall wird der Benutzer
 * normalerweise auf die index-Seite geleitet.
 * Ist jedoch Parameter $blnRedirect==false, so wird stattdessen ''
 * geliefert.
 *
 * @see redirect()
 * @param string $strName Der Name der Variablen.
 * @param bool $blnRedirect Soll eine Weiterleitung stattfinden
 * @return string Wert der Variablen
 */
function session($strName, $blnRedirect=true) {
  if (!isset($_SESSION[$strName])) {
    if (!$blnRedirect) {
      return '';
    }
    redirect('index.php');
  }
  else {
    return $_SESSION[$strName];
  }
}

/**
 * Loeschen von Session-Variablen
 *
 * Diese Funktion loescht eine Sessionvariable aus dem Speicher
 *
 * @param string $strName Der Name der Variablen.
 */
function session_delete($strName) {
  if (isset($_SESSION[$strName])) {
    unset($_SESSION[$strName]);
  }
}

/**
 * Liefert die Integer-Repraesentation des Booleans $blnProgram zur Speicherung
 * in der Datenbank.
 *
 * @param bool $blnProgram Boolean
 * @return int 0, falls $blnProgram = false, und 1 sonst.
 * @author Tom (12.01.05)
 * @access protected
 */
function b2db($blnProgram) {
  return ($blnProgram ? 1 : 0);
}

/**
 * Liefert die Boolean-Repraesentation des Datenbank-Integers $intDatabase zur
 * Verwendung im Programm.
 *
 * @param int $intDatabase Integer
 * @return bool false gdw. $intDatabase leer (bzw. 0) ist.
 * @author Tom (12.01.05)
 * @access protected
 */
function db2b($intDatabase) {
  return (empty($intDatabase) ? false : true);
}

/**
 * Liefert einen gueltigen String zur Speicherung in der Datenbank.
 *
 * @param string $strSql zu speichernder String
 * @return string korrekt kodierter String
 * @author Jan (18.01.05)
 * @access protected
 */
function s2db($strSql) {
  // muss hier noch mehr? PHP 4.3!!!
  $strSql = mysql_real_escape_string($strSql);
  return $strSql;
}

?>