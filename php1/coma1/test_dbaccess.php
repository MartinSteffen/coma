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
require_once('./include/class.personalgorithmic.inc.php');

function bit($b) {
  for ($i = 15; $i >= 0; $i--) {
    echo(($b & (1 << $i)) ? '1':'0');
  }
  echo('<br>');
  return true;
}

echo('<br>');


$myDBAccess->getAverageRatingOfPaper(3);

/*$p = $myDBAccess->addConference('Neue Conf', 'http', '', '', '', '', '', '', '', '',
                                2, 4, 20, 25, 0.5, false,  true, true, 1,
                                array(), array(), array(), array(), array());
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}*/

/*echo('Get<br>');
$p = $myDBAccess->getConferenceDetailed(1);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}
echo('Update<br>');
$myDBAccess->updateConference($p);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
echo('End<br>');*/

/*echo('Get<br>');
$p = $myDBAccess->getPaperDetailed(55);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}
echo ('<br><br>About Topics:');
for ($i = 0; $i < count($p->objTopics); $i++) {
  echo ('<br>'.$p->objTopics[$i]->intId.' / '.$p->objTopics[$i]->strName);
}
echo('Update<br>');
$myDBAccess->updatePaper($p);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
echo('End<br>');*/

/*$p = $myDBAccess->getPersonAlgorithmic(1, 5);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}

$myDBAccess->updatePreferredTopics($p, 5);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}

$myDBAccess->updatePreferredPapers($p, 5);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}

$myDBAccess->updateDeniedPapers($p, 5);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}

$myDBAccess->updateExcludedPapers($p, 5);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}

echo ('<br><br>Pref. Topics:');
for ($i = 0; $i < count($p->objPreferredTopics); $i++) {
  echo ('<br>'.$p->objPreferredTopics[$i]->intId.' / '.$p->objPreferredTopics[$i]->strName);
}
echo ('<br><br>Pref. Papers:');
for ($i = 0; $i < count($p->objPreferredPapers); $i++) {
  echo ('<br>'.$p->objPreferredPapers[$i]->intId.' / '.$p->objPreferredPapers[$i]->strTitle);
}
echo ('<br><br>Den. Papers:');
for ($i = 0; $i < count($p->objDeniedPapers); $i++) {
  echo ('<br>'.$p->objDeniedPapers[$i]->intId.' / '.$p->objDeniedPapers[$i]->strTitle);
}
echo ('<br><br>Excl. Papers:');
for ($i = 0; $i < count($p->objExcludedPapers); $i++) {
  echo ('<br>'.$p->objExcludedPapers[$i]->intId.' / '.$p->objExcludedPapers[$i]->strTitle);
}

*/

?>