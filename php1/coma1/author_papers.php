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

$content = new Template(TPLPATH.'author_paperlist.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

//nur fuer Veranschaulichungszwecke
require_once('./include/class.paperdetailed.inc.php');
$testpapers = array();
$paper0ids = array(3, 2);
$paper0names = array('Josef Huettenkeueler', 'Franz Hans');
$paper0topics = array('Schaum', 'Blasen');
$testpapers[0] = new PaperDetailed(23, 'Schaumparties', 1, 'Kuh', 0, 2.3, $paper0ids, $paper0names, 'http://snert.informatik.uni-kiel.de:8080/~swprakt/phpBB2', 'text/html', '01-01-2005', 'http://snert.informatik.uni-kiel.de:8080/~swprakt/phpBB2', $paper0topics);

$none = true;
$strContentAssocs['paper_rows'] = '';
foreach ($testpapers as $paper){
  $none = false;
  $papertemplate = new Template(TPLPATH . 'author_paperlistitem.tpl');
  $paperassocs = defaultAssocArray();
  $paperassocs['title'] = encodeText($paper->strTitle);
  $paperassocs['status'] = $paper->intStatus;
  $paperassocs['avg_rating'] = $paper->fltAvgRating;  
  $paperassocs['file_link'] = encodeURL($paper->strFilePath);
  $paperassocs['last_edited'] = $paper->strLastEdit;
  $paperassocs['paper_id'] = $paper->intId;
  $papertemplate->assign($paperassocs);
  $papertemplate->parse();
  $strContentAssocs['paper_rows'] = $strContentAssocs['paper_rows'] . $papertemplate->getOutput();
}

if (!empty($none)) {
  $strContentAssocs['paper_rows'] = 'There are no items in this category. Use the form below to upload papers.';
}
$content->assign($strContentAssocs);
$actMenu = AUTHOR;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Manage papers of author '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Author  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
