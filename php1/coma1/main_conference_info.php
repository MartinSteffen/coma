<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo rework completly
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
require_once('./include/header.inc.php');

// Lade die Daten der Konferenz
if (isset($_POST['confid'])) {
  $objConference = $myDBAccess->getConferenceDetailed($_POST['confid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
  }
}
else {
  redirect('main_conferences.php');
}

$content = new Template(TPLPATH.'conference_info.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(2);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Conference Description';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Conferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>