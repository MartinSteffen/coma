<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo Inhalte!
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
/**
 * User muss auf der Seite nicht eingeloggt sein
 *
 * @ignore
 */
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

$strMainAssocs = defaultAssocArray();

if (checklogin()) {
  $strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Help';
  if (isset($_SESSION['confid'])) {
    $actMenu = 0;
    $actMenuItem = 0;
    include('./include/usermenu.inc.php');
  }
  else {
    $menu = new Template(TPLPATH.'mainmenu.tpl');
    $strMenuAssocs = defaultAssocArray();
    $strMenuAssocs['if'] = array();
    $menu->assign($strMenuAssocs);    
  }  
}
else {
  $menu = new Template(TPLPATH.'startmenu.tpl');
  $strMenuAssocs = defaultAssocArray();
  $strMenuAssocs['if'] = array(3);
  $menu->assign($strMenuAssocs);
  $strMainAssocs['navigator'] = 'CoMa  |  Help';
}

$content = new Template(TPLPATH.'helpmain.tpl');
$content->assign(defaultAssocArray());

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs['title'] = 'Getting started with CoMa';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
