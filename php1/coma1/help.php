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

$content = new Template(TPLPATH.'help.tpl');
$strContentAssocs = defaultAssocArray();

//angeforderte hilfethemen bestimmen und template zusammenbauen
$strArrayHelpTopics = explode(' ',$_GET['for']);
var_dump($strArrayHelpTopics);

//nur zu testzwecken
$toctemplate = new Template(TPLPATH . 'help_chapter.tpl');
$tocassocs = defaultAssocArray();
$tocassocs['chapter-no'] = '0';
$tocassocs['chapter-title'] = 'Table of contents';
$tocassocs['related-topics'] = '';
$tocassocs['special'] = 'example';
$tocassocs['related-link'] = '';
$toccontent = new Template(TPLPATH . 'toc.tpl');
$toccontentassocs = defaultAssocArray();
$toccontentassocs['popup'] = '';
$toccontent->assign($toccontentassocs);
$toccontent->parse();
$tocassocs['content'] = $toccontent->getOutput();
$toctemplate->assign($tocassocs);
$toctemplate->parse();
$strContentAssocs['toc'] = $toctemplate->getOutput();
//---

$strContentAssocs['navlink'] = ($popup) ? array( 'CLOSE' ) : array( 'BACK' );
$content->assign($strContentAssocs);

if (!$popup) {
  $main = new Template(TPLPATH.'frame.tpl');
  $strMainAssocs['menu'] = &$menu;
}
else {
  $main = new Template(TPLPATH.'popup_frame.tpl');
}
$strMainAssocs['title'] = 'Helping you when you are in a CoMa';
$strMainAssocs['content'] = &$content;

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
