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

// Security :)
checkAccess(0);

$popup = (isset($_GET['popup'])) ? true : false;

$strMainAssocs = defaultAssocArray();

if (checklogin()) {
  $strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Help';
  if (!$popup) {
    if (isset($_SESSION['confid'])) {
      $_SESSION['menu'] = 0;
      $_SESSION['menuitem'] = 5;
      include('./include/usermenu.inc.php');
    }
    else {
      $menu = new Template(TPLPATH.'mainmenu.tpl');
      $strMenuAssocs = defaultAssocArray();
      $strMenuAssocs['if'] = array(5);
      $menu->assign($strMenuAssocs);
    }
  }
}
else {
  if (!$popup) {
    $menu = new Template(TPLPATH.'startmenu.tpl');
    $strMenuAssocs = defaultAssocArray();
    $strMenuAssocs['if'] = array(3);
    $menu->assign($strMenuAssocs);
  }
  $strMainAssocs['navigator'] = 'CoMa  |  Help';
}

$content = new Template(TPLPATH.'helpmain.tpl');
$strContentAssocs = defaultAssocArray();

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
  $strContentAssocs['keyword'] = encodeText($_GET['keyword']);
}
else {
  $strContentAssocs['keyword'] = 'no given special Page';
}

$strContentAssocs['localhelp'] = 'TODO: This should be a localized help text for every Situation '.
  '(get location out of Session? or via GET?)<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$strContentAssocs['navlink'] = ($popup) ? array( 'CLOSE' ) : array( 'BACK' );
$content->assign($strContentAssocs);

if (!$popup) {
  $main = new Template(TPLPATH.'frame.tpl');
  $strMainAssocs['menu'] = &$menu;
}
else {
  $main = new Template(TPLPATH.'popup_frame.tpl');
}
$strMainAssocs['title'] = 'Getting started with CoMa';
$strMainAssocs['content'] = &$content;

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
