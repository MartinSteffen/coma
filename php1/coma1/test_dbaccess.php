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

require_once('./include/header.inc.php');

$id = $myDBAccess->getPersonIdByEmail('hase@braten.org');
echo('<br>ID = '.$id.'<br>');
$p = $myDBAccess->getPersonDetailed($id);
echo($p->strFirstName.' '.$p->strLastName.' '.$p->intRoles.'<br>');
echo($p->strFirstName.' ist '.($p->hasRole(1)?'':'k').'ein Chair.<br>');
echo($p->strFirstName.' ist '.($p->hasRole(2)?'':'k').'ein Reviewer.<br>');
echo($p->strFirstName.' ist '.($p->hasRole(5)?'':'k').'ein Teilnehmer.<br><br>');

$p = $myDBAccess->getPapersOfAuthor(1);
if (!empty($p)) {
  for ($i = 0; $i < count($p); $i++) {
    echo('Titel: '.$p[$i]->strTitle.', Autor: '.$p[$i]->strAuthor.'<br>');
  }
}

echo('rating of review #1 = '.$myDBAccess->getReviewRating(1).'<br>');
echo('rating of review #2 = '.$myDBAccess->getReviewRating(2).'<br>');
echo('avg rating of paper #1 = '.$myDBAccess->getAverageRatingOfPaper(1).'<br>');

$p = $myDBAccess->getPaperDetailed(1);
if (!empty($p)) {
  echo('Autor (ID) / Titel: '.$p->strAuthor.' ('.$p->intAuthorId.') / '.$p->strTitle.'<br>');
  echo('Co-Autoren (ID):<br>');
  for ($i = 0; $i < count($p->intCoAuthorIds); $i++) {
    echo($p->strCoAuthors[$i].' ('.$p->intCoAuthorIds[$i].')');
  }
}
else echo('Else sagt: \"Nix gefunden...\"');


?>