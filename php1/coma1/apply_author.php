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

if (!isset($_POST['confid'])) {
  redirect('main_conferences.php');
}

// Lade die Daten der Person
$objPerson = $myDBAccess->getPersonDetailed(session('uid'), $_POST('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving actual person.', $myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'apply_author.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(2);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Conferences Overview';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Conferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>