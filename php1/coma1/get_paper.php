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
  // Hole dir das File
  $file = $myDBAccess->getPaperFile($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured retrieving paper.', $myDBAccess->getLastError());
  }
  if (empty($file)) {
    error('Error occured retrieving paper.', 'File not found!');
  }
  // Sende das File
  $name = $file[0];
  $type = $file[1];
  header("Content-type: $type");
  //header("Content-length: $size");
  header("Content-Disposition: attachment; filename=$name");
  header("Content-Description: Downloadable Paper");
  echo $file[2]
}
else {
  // Keine PaperID
  error('Get Paper', 'No Paper to get...');
}


?>