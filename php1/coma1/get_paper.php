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
  //checkAccess(0);
  $objPaper = $myDBAccess->getPaperSimple($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured retrieving paper.', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('Get paper','Paper '.$intPaperId.' does not exist in database!');
  }  
  $checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR)    ||
               $myDBAccess->hasRoleInConference(session('uid'), session('confid'), REVIEWER) ||
               (session('uid') == $objPaper->intAuthorId);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
  }
  else if (!$checkRole) {
    error('You have no permission to view this page.', '');
  }  
  // Hole dir das File
  $file = $myDBAccess->getPaperFile($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured retrieving paper', $myDBAccess->getLastError());
  }
  if (empty($file)) {
    error('Error occured retrieving paper', 'File not found!');
  }
  // Sende das File
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: public");
  header("Content-type: {$file[1]}");
  header("Content-length: {$file[2]}");
  header("Content-Disposition: attachment; filename={$file[0]}");
  header("Content-Description: Downloadable Paper");
  header("Content-Transfer-Encoding: binary");
  echo $file[3];
}
else {
  // Keine PaperID
  error('Get Paper', 'No Paper to get...');
}

?>