<?php
/**
 * @version $Id
 * @package coma1
 * @subpackage Testing
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */

define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);

require_once('./include/header.inc.php');
require_once('./include/class.distribution.inc.php');

function bit($b) {
  for ($i = 15; $i >= 0; $i--) {
    echo(($b & (1 << $i)) ? '1':'0');
  }
  echo('<br>');
  return true;
}

echo('<br>');


$myDist = new Distribution($mySql);

$myDist->getDistribution(1);
if ($myDist->failed()) {
  echo($myDist->getLastError());
}
else if (empty($p)) {
  echo('<br>EMPTY');
  die(-1);
}
echo('OK');

?>