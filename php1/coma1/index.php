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
$myDBAccess->getPerson(session('uid'));
echo session('uid');
if ($myDBAccess->failed()) {
  error('index.php -> chooseHighestUserlevel',$myDBAccess->getLastError());
}
redirect('chair_start.php');
redirect('reviewer_start.php');
redirect('author_start.php');
redirect('participant_start.php');
// falls man kein Userlevel haben sollte, sollte man auchnicht eingeloggt sein!
redirect('user_conference.php');

?>