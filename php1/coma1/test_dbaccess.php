<?php
/**
 * @version $Id$
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

function bit($b) {
  for ($i = 15; $i >= 0; $i--) {
    echo(($b & (1 << $i)) ? '1':'0');
  }
  echo('<br>');
  return true;
}

$p = $myDBAccess->getPerson(1, 1);

$p->intRoles = 10;
bit($p->intRoles);
$p->addRole(1);
bit($p->intRoles);
$p->switchRole(4);
bit($p->intRoles);
$p->deleteRole(2);
bit($p->intRoles);

echo('Roles:<br>');
for ($i = 0; $i < count($intRoles); $i++) {
  if ($p->hasRole($intRoles[$i]))
    echo('p hat Rolle '.$strRoles[$intRoles[$i]].'<br>');
}
echo('Aendere Rollen:<br>');
$p->deleteRole(REVIEWER);
$p->switchRole(AUTHOR);
$p->addRole(CHAIR);
echo('Roles:<br>');
for ($i = 0; $i < count($intRoles); $i++) {
  if ($p->hasRole($intRoles[$i]))
    echo('p hat Rolle '.$strRoles[$intRoles[$i]].'<br>');
}
//echo('Update der Rollen in der DB...<br>');
//$myDBAccess->updateRoles(1, $p);
echo('Neuladen der Person:<br>');
$p = $myDBAccess->getPerson(1);
echo('Roles:<br>');
for ($i = 0; $i < count($intRoles); $i++) {
  if ($p->hasRole($intRoles[$i]))
    echo('p hat Rolle '.$strRoles[$intRoles[$i]].'<br>');
}


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