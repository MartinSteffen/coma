<?php
/**
 * @version $Id
 * @package coma1
 * @subpackage Testing
 */
/***/

/*** @ignore */
define('IN_COMA1', true);
/*** @ignore */
define('NEED_NO_LOGIN', true);

include('./include/header.inc.php');
include('./include/getCriticalPapers.inc.php');
$papervars = getCriticalPapers();
echo ('<html><body>');
foreach ($papervars as $papervar){
  echo('paper id=' . $papervar->intId . ' var=' . $papervar->fltVariance . ';');
}
echo('</body></html>');

?>