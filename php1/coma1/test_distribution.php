<?php
/**
 * @version $Id
 * @package coma1
 * @subpackage Testing
 */
/***/

/**@ignore */
define('IN_COMA1', true);
/**@ignore */
define('NEED_NO_LOGIN', true);

require_once('./include/header.inc.php');
require_once('./include/class.distribution.inc.php');



echo('<br>');

$x = 'p724r84';

$p = array();
$r = array();
preg_match('/p[\d]+/', $x, $p);
preg_match('/r[\d]+/', $x, $r);
print_r($p);
print_r($r);

exit(-1);


$myDist = new Distribution($mySql);

$y = $myDist->getDistribution(1);
if ($myDist->failed()) {
  echo($myDist->getLastError());
}
else if (empty($y)) {
  echo('<br><br>EMPTY');
  die(-1);
}
echo('<br><br>OK:');
print_r($y);
/*for ($i = 0; $i < count($m); $i++) {
  $s = sprintf("<br>%d:", $i);
  for ($j = 0; $j < count($m[$i]); $j++) {
    $s = $s.sprintf(" %d", $m[$i][$j]);
  }
  echo($s);
}*/

?>