<?php
/**
 * Funktionen und Konstanten fuer alle Skripte
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}

/**#@+ Konstanten fuer die Rollenverteilung */
define('CHAIR',       2);
define('REVIEWER',    3);
define('AUTHOR',      4);
define('PARTICIPANT', 5);
$intRoles = array(CHAIR, REVIEWER, AUTHOR, PARTICIPANT);
$strRoles = array(CHAIR       => 'Chair',
                  REVIEWER    => 'Reviewer',
                  AUTHOR      => 'Author',
                  PARTICIPANT => 'Participant');
/**#@-*/

/**#@+ Konstanten fuer die Artikelstatuswerte */
define('PAPER_UNREVIEWED', 0);
define('PAPER_REVIEWED',   1);
define('PAPER_CRITICAL',   2);
define('PAPER_ACCEPTED',   3);
define('PAPER_REJECTED',   4);
/**#@-*/

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
  (@include(TPLPATH.'error.php')) or print($strError);
  die(1);
}

/**
 * Weiterleitung auf ein anderes Skript
 *
 * Diese Funktion lenkt den Benutzer auf einen anderen Skript weiter, in
 * dem die Bearbeitung fortgeführt wird. Dabei wird sichergestellt, das
 * Sessioninformationen erhalten bleiben.
 *
 * @warning nur vor irgendwelchen Ausgaben aufrufen!
 * @param string $strName Das aufzurufende Skript.
 */
function redirect($strName) {
  global $mySession;
  session_write_close();
  $strTarget = COREURL . $strName . getUrlId('?');
  header('Location:' . $strTarget);
  die('Going to: '.$strName.'<br>'.
      'If your Browser does not support automatical redirection please use '.
      '<a href="'.$strTarget.'">this link!</a>');
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
               'path'      => encodeText(TPLURL),
               'basepath'  => encodeText(COREURL),
               'filename'  => encodeText(basename($_SERVER['PHP_SELF'],'.php')),
               // last 2 are enconded already
               '?SID'       => getUrlId('?'),
               '&SID'       => getUrlId('&amp;')
              );
}

/**
 * Anzuhaengende URL fuer Verweise
 *
 * Falls ein Cookie gesetzt wurde, gibt die Funktion leer zurueck. Ansonsten
 * wird '?SessionName=SessionId' zurueck gegeben. Dieses kann also einfach an
 * alle Skript-Verweise angehaengt werden.
 *
 * @param string $strPrefix Prefix fuer SID zB '?'
 * @return string Anhang fuer URL
 * @access public
 */
function getUrlId($strPrefix='') {
  return (SID == '') ? '' : $strPrefix . encodeText(SID);
}

/**
 * This function encodes the string.
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function nl2tag($_str) {
  $tag = @file_get_contents(TPLPATH.'newlinetag.tpl');
  return str_replace("\n", $tag."\n", $_str);
}

/**
 * This function encodes the string.
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function encodeText($_str) {
  $_str = strtr(trim($_str), "|", " ");
  return nl2tag(htmlentities($_str, ENT_QUOTES, 'UTF-8'));
}


/**
 * This function transforms the given string into an array of strings
 * and encodes the single entries of the array.
 *
 * @param string $_str String representing array of strings to encode
 * @return string encoded string array
 */
function encodeTextArray($_str) {
  if (empty($_str)) {
    return array();
  }
  $retArray = explode('|', $_str);
  for ($i = 0; $i < count($retArray); $i++) {
    $retArray[$i] = encodeText($retArray[$i]);
  }
  return $retArray;
}

/**
 * This function makes an encoded String URL valid (gives an valid Link!)
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function encodeURL($_str) {
  if (empty($_str)) return '';
  $_str = str_replace('\'', urlencode('\''), $_str);
  $_str = str_replace('"', urlencode('"'), $_str);
  if (!preg_match('#^[a-z]+:\/\/#i', $_str)) {
    $_str = 'http://' . $_str;
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
 * Diese Funktion ueberprueft ob der aktuelle Benutzer korrekt eingeloggt ist.
 *
 * @param int $role Die Rolle in der Konferenz 0 fuer beliebige
 * @return bool True gdw User darf ;)
 */
function checkAccess($role) {
  global $myDBAccess;
  if (!$myDBAccess->hasRoleInConference(session('uid'), session('confid'), $role)) {
    if ($myDBAccess->failed()) {
      error('Error occured retrieving conference data.', $myDBAccess->getLastError());
    }
    else if (!$checkRole) {
      error('You have no permission to view this page.', '');
    }
  }
  return true;
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
    error('session()',$strName.' not found!');
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

/**
 * Liefert ein nach $format formatiertes Datum zurueck.
 *
 * @param string $str das Datum als Timestamp!!
 * @param string $format Formatierung (siehe date)
 * @return string das formatierte Datum
 * @author Jan (24.01.05)
 * @access protected
 */
function emptytime($str, $format='M d, Y') {
  return empty($str) ? '' : date($format, $str);
}

/**
 * Liefert ein nach $format formatiertes Datum zurueck.
 *
 * @param string $str das Datum als String
 * @param string $format Formatierung (siehe date)
 * @return string das formatierte Datum
 * @author Jan (24.01.05)
 * @access protected
 */
function emptyDBtime($str, $format='M d, Y') {
  return (empty($str) || ($str == '0000-00-00') || ($str == '0000-00-00 00:00:00')) ?
    '' : date($format, strtotime($str));
}

/**
 * float chronometer()
 *
 * Enables determination of an amount of time between two points in a script,
 * in milliseconds.
 * Call the function a first time to start the chronometer. The next
 * call to the function will return the number of milliseconds elapsed since
 * the chronometer was started (rounded to three decimal places).
 * The chronometer is then available for being started again.
 *
 */
function chronometer()
{
  static $CHRONO_STARTTIME = 0;
  $now = (float) array_sum(explode(' ', microtime()));

  if ($CHRONO_STARTTIME > 0)
  {
    $retElapsed = round($now * 1000 - $CHRONO_STARTTIME * 1000, 3);
    $CHRONO_STARTTIME = 0;
    return $retElapsed;
  }
  else
  {
    $CHRONO_STARTTIME = $now;
    return 0;
  }
}

/**
 * Versendet eine E-Mail
 *
 * @param int $intUserId an diesen wird geschickt
 * @param string $strSubject dieses ist der Betreff
 * @param string $strMsg dieses ist eine spannende Nachricht
 * @param string $strFrom dieses ist 1. optional und 2. der Absender
 * @return bool True gdw. Erfolgreich
 * @author Jan&Tom (31.01.05)
 * @access public
 */
function sendMail($intUserID, $strSubject, $strMsg, $strFrom='')
{
  global $myDBAccess;
  $objPerson = $myDBAccess->getPerson($intUserID);
  if ($myDBAccess->failed()) {
    error('sendMail',$myDBAccess->getLastError());
  }
  if (empty($objPerson)) {
    error('sendMail', "UserID $intUserID not found in database!");
  }
  if (empty($strFrom)) {
    $strFrom = "\"CoMa - Your Conference Manager\" <>";
  }

  $strMsg = utf8_encode($strMsg);
  $strSubject = utf8_encode('[CoMa] '.$strSubject);
  $name = $objPerson->getName(2);
  // \n vs \r\n vs \r fix via relying on downloading it from svn using correct system :)
  $header = <<<HEADER
To: "{$name}" <{$objPerson->strEmail}>
From: {$strFrom}
MIME-Version: 1.0
Content-type: text/plain; charset=utf-8
HEADER;
  return mail('', $strSubject, $strMsg, $header);
}


/**
 *  Generate Random Password
 *
 * @param int $intLen Die Laenge des Passwortes (bis Maximal 160)
 * @return string Das Passwort
 * @author Jan (31.01.05)
 * @access public
 */
function generatePassword($intLen=8) {
  $rnd_id = crypt(uniqid(rand(), 1));
  $rnd_id = strip_tags(stripslashes($rnd_id));
  $rnd_id = str_replace('.', '', $rnd_id);
  $rnd_id = strrev(str_replace('/', '',$rnd_id));
  $rnd_id = substr($rnd_id, 0, $intLen); 
  return $rnd_id;
}

?>