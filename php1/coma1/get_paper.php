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
require_once('./include/header.inc.php');

if (isset($_GET['paperid'])) {
  checkAccess(0);
  /* Das hier besser? So noch nciht, fehlt getAuthorId, ausserdem ErrorCheck nicht korrekt!!
  $checkRole = ($myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR))
             ||($myDBAccess->hasRoleInConference(session('uid'), session('confid'), REVIEWER))
             ||(session('uid') == $intAuthorId);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
  }
  else if (!$checkRole) {
    error('You have no permission to view this page.', '');
  }
  */
  // Hole dir das File
  $file = $myDBAccess->getPaperFile($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured retrieving paper', $myDBAccess->getLastError());
  }
  if (empty($file)) {
    error('Error occured retrieving paper', 'File not found!');
  }
  // Sende das File
  $name = $file[0];
  $type = $file[1];
  $size = $file[2];
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: public");
  header("Content-type: $type");
  header("Content-length: $size");
  header("Content-Disposition: attachment; filename=$name");
  header("Content-Description: Downloadable Paper");
  header("Content-Transfer-Encoding: binary");
  echo $file[3];
}
else {
  // Keine PaperID
  error('Get Paper', 'No Paper to get...');
}

?>