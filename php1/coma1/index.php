<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

if ((!isset($_SESSION['uname']))||(!isset($_SESSION['password']))||(!checkLogin())) {
  redirect('login.php');
}
// Wenn ich hier bin, bin ich eingeloggt!
if (!isset($_SESSION['confid'])) {
  redirect('main_start.php');  
}
// Eingeloggt und hab ne Konfernez gewaehlt -> Userlevel bestimmen
// Sicherheitshalber einfahc mal ueberpruefen
if (!isset($_SESSION['uid'])) {
  // UID setzen
  $_SESSION['uid'] = $myDBAccess->getPersonIdByEmail(session('uname'));
  if ($myDBAccess->failed()) {
    session_delete('uid');
    error('getUID',$myDBAccess->getLastError());
  }
}
$objIch = $myDBAccess->getPerson(session('uid'));
if ($myDBAccess->failed()) {
  error('chooseHighestUserlevel',$myDBAccess->getLastError());
}
if ($objIch->hasRole(CHAIR)) {
  redirect('chair_start.php');
}
if ($objIch->hasRole(REVIEWER)) {
  redirect('reviewer_start.php');
}
if ($objIch->hasRole(AUTHOR)) {
  redirect('author_start.php');
}
if ($objIch->hasRole(PARTICIPANT)) {  
  redirect('participant_start.php');
}
// falls man kein Userlevel haben sollte, sollte man auch nicht hier sein!
error('detect UserLevel','Unknown UserLevel!');

?>