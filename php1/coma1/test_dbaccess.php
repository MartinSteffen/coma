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
require_once('./include/class.personalgorithmic.inc.php');

function bit($b) {
  for ($i = 15; $i >= 0; $i--) {
    echo(($b & (1 << $i)) ? '1':'0');
  }
  echo('<br>');
  return true;
}

echo('<br>');

$p = $myDBAccess->getPersonAlgorithmic(1, 5);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}
echo ('<br>Pref. Topics:<br>');
for ($i = 0; $i < count($p->objPreferredTopics); $i++) {
  echo ('<br>'.$p->objPreferredTopics[$i]->intId.' / '.$p->objPreferredTopics[$i]->strName);
}
echo ('<br>Pref. Papers:<br>');
for ($i = 0; $i < count($p->objPreferredPapers); $i++) {
  echo ('<br>'.$p->objPreferredPapers[$i]->intId.' / '.$p->objPreferredPapers[$i]->strTitle);
}




/*
$p = $myDBAccess->getPaperDetailed(2);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}
$p->intTopics[0] = 4;
$p->intTopics[] = 1;
$myDBAccess->updatePaper($p);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
echo('OK');*/

/*
$c = $myDBAccess->getConferenceDetailed(1);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($c)) {
  echo('EMPTY');
  die(-1);
}
$c->objTopics[0]->strName = $c->objTopics[0]->strName.' (Update)';
$myDBAccess->updateConference($c);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
echo ('OK');*/


/*
$p = $myDBAccess->getPaperDetailed(2);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else if (empty($p)) {
  echo('EMPTY');
  die(-1);
}
echo("<br>Papertitel: $p->strTitle<br>");
$p->strTitle = strtolower($p->strTitle);
echo('Co-Autoren:<br>');
for ($i = 0; $i < count($p->intCoAuthorIds); $i++) {
  echo ($p->intCoAuthorIds[$i].' / '.$p->strCoAuthors[$i].'<br>');
}
//$p->intCoAuthorIds[] = false;
//$p->strCoAuthors[] = 'John Kerry';
//$p->intCoAuthorIds[] = $p->intCoAuthorIds[0];
//$p->strCoAuthors[] = false;
//$p->intCoAuthorIds[0] = false;
//$p->strCoAuthors[0] = false;
$myDBAccess->updatePaper($p);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
echo('OK');*/

/*$id = $myDBAccess->addConference('Angebranntes Sommerheu und andere Betaeubungsmittel',
                           '', '', '', '', '', '', '', '', '');
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else echo("Eingefuegt: $id");
$myDBAccess->deleteConference($id);
if ($myDBAccess->failed()) {
  echo($myDBAccess->getLastError());
}
else echo("Geloescht: $id");*/

/*$p = $myDBAccess->getPerson(1, 1);
if ($myDBAccess->failed()) {
  echo ('Fehler: '.$myDBAccess->getLastError());
  die(-1);
}
echo('<br>Roles:<br>');
for ($i = 0; $i < count($intRoles); $i++) {
  if ($p->hasRole($intRoles[$i]))
    echo('p hat Rolle '.$strRoles[$intRoles[$i]].'<br>');
}
bit($p->intRoles);
echo('<br>Aendere Rollen:<br>');
if ($p->hasRole(REVIEWER)) {
  $p->deleteRole(REVIEWER);
  $p->switchRole(AUTHOR);
  $p->addRole(CHAIR);
}
else {
  $p->addRole(REVIEWER);
  $p->switchRole(AUTHOR);
  $p->deleteRole(CHAIR);
}
bit($p->intRoles);
echo('<br>Roles:<br>');
for ($i = 0; $i < count($intRoles); $i++) {
  if ($p->hasRole($intRoles[$i]))
    echo('p hat Rolle '.$strRoles[$intRoles[$i]].'<br>');
}
echo('<br>Update der Rollen in der DB...<br>');
$myDBAccess->updateRoles($p->intRoles, 1);
if ($myDBAccess->failed()) {
  echo ('Fehler: '.$myDBAccess->getLastError());
  die(-1);
}
echo('<br>Neuladen der Person:<br>');
$p = $myDBAccess->getPerson(1, 1);
echo('Roles:<br>');
for ($i = 0; $i < count($intRoles); $i++) {
  if ($p->hasRole($intRoles[$i]))
    echo('p hat Rolle '.$strRoles[$intRoles[$i]].'<br>');
}*/

/*$id = $myDBAccess->getPersonIdByEmail('rr@hase.de');
echo('<br>ID = '.$id.'<br>');
$p = $myDBAccess->getRoles($id,1);
echo $p[0];*/

/*$_SESSION['confid'] = 1;
echo('<br>Konferenz-ID: '.$_SESSION['confid']);
$cd = $myDBAccess->getConferenceDetailed();
if (!empty($cd)) {
  echo('<br>AutoActivateAccount: '.$cd->blnAutoActivateAccount);
  if ($cd->blnAutoActivateAccount = true) echo('TRUE');
  if ($cd->blnAutoActivateAccount = false) echo('FALSE');
  echo('<br>AutoOpenPaperForum:  '.$cd->blnAutoOpenPaperForum);
  if ($cd->blnAutoOpenPaperForum = true) echo('TRUE');
  if ($cd->blnAutoOpenPaperForum = false) echo('FALSE');
  echo('<br>AutoAddReviewers:    '.$cd->blnAutoAddReviewers);
  if ($cd->blnAutoAddReviewers = true) echo('TRUE');
  if ($cd->blnAutoAddReviewers = false) echo('FALSE');
}
else {
  echo('<br>LastError: '.$myDBAccess->getLastError());
}*/


/*
$id = $myDBAccess->getPersonIdByEmail('rr@hase.de');
echo('<br>ID = '.$id.'<br>');
$p = $myDBAccess->getPersonDetailed($id);
echo($p->strFirstName.' '.$p->strLastName.' '.$p->intRoles.'<br>');
echo($p->strFirstName.' ist '.($p->hasRole(1)?'':'k').'ein Chair.<br>');
echo($p->strFirstName.' ist '.($p->hasRole(2)?'':'k').'ein Reviewer.<br>');
echo($p->strFirstName.' ist '.($p->hasRole(5)?'':'k').'ein Teilnehmer.<br><br>');

$myDBAccess->deleteCoAuthorName(4, 'Meister Lampe');
echo($myDBAccess->getLastError());
*/

/*
$p = $myDBAccess->getAllConferences();
for ($i = 0; $i < count($p); $i++) {
   echo $p[0]->strName;
}

echo($myDBAccess->getLastError());
  */



/*
$p = $myDBAccess->getPapersOfAuthor(1);
if (!empty($p)) {
  for ($i = 0; $i < count($p); $i++) {
    echo('Titel: '.$p[$i]->strTitle.', Autor: '.$p[$i]->strAuthor.'<br>');
  }
}

echo('<br>rating of review #1 = '.$myDBAccess->getReviewRating(1).'<br>');
echo('rating of review #2 = '.$myDBAccess->getReviewRating(2).'<br>');
echo('avg rating of paper #1 = '.$myDBAccess->getAverageRatingOfPaper(1).'<br><br>');

$p = $myDBAccess->getPaperDetailed(1);
if (!empty($p)) {
  echo('Autor (ID) / Titel:<br>');
  echo($p->strAuthor.' ('.$p->intAuthorId.') / '.$p->strTitle.'<br>');
  echo('Co-Autoren (ID):<br>');
  for ($i = 0; $i < count($p->intCoAuthorIds); $i++) {
    echo($p->strCoAuthors[$i].' ('.$p->intCoAuthorIds[$i].')<br>');
  }
}
else {
  echo('Else sagt: Nix gefunden, weil:<br>');
  echo($myDBAccess->getLastError());
}

$p = $myDBAccess->getPapersOfReviewer(2);
echo('<br>Papers von Reviewer #2:<br>');
if (!empty($p)) {
  for ($i = 0; $i < count($p); $i++) {
    echo($p[$i]->strTitle);
  }
}
else {
  echo('Else sagt: Nix gefunden, weil:<br>');
  echo($myDBAccess->getLastError());
}
*/

?>