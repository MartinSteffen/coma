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

// Lade die Daten des Autoren
$objAuthor = $myDBAccess->getPersonDetailed(session('uid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving author.', $myDBAccess->getLastError());
}
else if (empty($objAuthor)) {
  error('Author does not exist in database.', '');
}
$objAllTopics = $myDBAccess->getTopicsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'create_paper.tpl');
$strContentAssocs = defaultAssocArray();


$strContentAssocs['author_name'] = encodeText($objAuthor->getName());
$strContentAssocs['coauthors_num'] = encodeText(count($strCoAuthors));
$strContentAssocs['coauthor_lines'] = '';
for ($i = 0; $i < count($strCoAuthors); $i++) {
  $coauthorForm = new Template(TPLPATH.'paper_coauthorlistitem.tpl');
  $strCoauthorAssocs = defaultAssocArray();
  $strCoauthorAssocs['coauthor_no'] = encodeText($i+1);
  $strCoauthorAssocs['coauthor']    = encodeText($objPaper->strCoAuthors[$i]);    
  $strCoauthorAssocs['if'] = array();
  $coauthorForm->assign($strCoauthorAssocs);
  $coauthorForm->parse();
  $strContentAssocs['coauthor_lines'] .= $coauthorForm->getOutput();
}
$strContentAssocs['targetpage'] = 'author_createpaper.php';
$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 9;
}
$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$actMenu = AUTHOR;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Add new paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Author  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
