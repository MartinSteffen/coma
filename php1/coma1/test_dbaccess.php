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
if($p) {
  for($i = 0; $i < count($p); $i++) {
    echo('Titel: '.$p[$i]->strTitle.', Autor: '.$p[$i]->strAuthor.'<br>');
  }
}

?>