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

$popup = (isset($_GET['popup'])) ? true : false;

$strMainAssocs = defaultAssocArray();

if (checklogin()) {
  $strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Help';
  if (!$popup) {
    if (isset($_SESSION['confid'])) {
      checkAccess(0);
      $_SESSION['menu'] = 0;
      $_SESSION['menuitem'] = 5;
      include(INCPATH.'usermenu.inc.php');
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
if (!empty($_GET['for'])){
  $strArrayHelpTopics = explode('_',$_GET['for']);
}
else{
  $strArrayHelpTopics = array('nothing');
}

if (in_array('toc', $strArrayHelpTopics)){
  $toctemplate = new Template(TPLPATH.'help_chapter.tpl');
  $tocassocs = defaultAssocArray();
  $tocassocs['chapter-no'] = '0';
  $tocassocs['chapter-title'] = 'Table of contents';
  $tocassocs['related-topics'] = '';
  $tocassocs['special'] = '';
  $tocassocs['related-link'] = '';
  $toccontent = new Template(TPLPATH.'toc.tpl');
  $toccontentassocs = defaultAssocArray();
  if (isset($_GET['popup'])){
    $toccontentassocs['&popup'] = '&amp;popup';
  }
  else{
    $toccontentassocs['&popup'] = '';
  }
  $toccontent->assign($toccontentassocs);
  $toccontent->parse();
  $tocassocs['content'] = $toccontent->getOutput();
  $toctemplate->assign($tocassocs);
  $toctemplate->parse();
  $strContentAssocs['toc'] = $toctemplate->getOutput();
}
if (in_array('nothing', $strArrayHelpTopics)){
  $toctemplate = new Template(TPLPATH.'help_chapter.tpl');
  $tocassocs = defaultAssocArray();
  $tocassocs['chapter-no'] = '0';
  $tocassocs['chapter-title'] = 'Table of contents';
  $tocassocs['related-topics'] = '';
  $tocassocs['special'] = '';
  $tocassocs['related-link'] = '';
  $toccontent = new Template(TPLPATH.'toc.tpl');
  $toccontentassocs = defaultAssocArray();
  if (isset($_GET['popup'])){
    $toccontentassocs['&popup'] = '&amp;popup';
  }
  else{
    $toccontentassocs['&popup'] = '';
  }
  $toccontent->assign($toccontentassocs);
  $toccontent->parse();
  $tocassocs['content'] = $toccontent->getOutput();
  $toctemplate->assign($tocassocs);
  $toctemplate->parse();
  $strContentAssocs['toc'] = $toctemplate->getOutput();
}
if (in_array('comapages', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2';
  $strArrayChapterAssocs['chapter-title'] = 'Detailed explanations about the different pages in CoMa';
  $strArrayChapterAssocs['related-topics'] = '';
  $strArrayChapterAssocs['special'] = '';
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02'] = $objChaptertemplate->getOutput();
}
if (in_array('login', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.1';
  $strArrayChapterAssocs['chapter-title'] = 'The login page / logging into your account';
  $strArrayChapterAssocs['related-topics'] = 'related topics:';
  $strArrayChapterAssocs['special'] = '';
  $objRelatedTemplate = new Template(TPLPATH.'help_relatedlink.tpl');
  $strArrayRelatedAssocs = defaultAssocArray();
  $strArrayRelatedAssocs['topics'] = 'register';
  if (isset($_GET['popup'])){
    $strArrayRelatedAssocs['&popup'] = '&popup';
  }
  else{
    $strArrayRelatedAssocs['&popup'] = '';
  }
  $strArrayRelatedAssocs['linkname'] = 'registering for an account';
  $objRelatedTemplate->assign($strArrayRelatedAssocs);
  $objRelatedTemplate->parse();
  $strArrayChapterAssocs['related-link'] = $objRelatedTemplate->getOutput();
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-01'] = $objChaptertemplate->getOutput();
}

$strContentAssocs['navlink'] = ($popup) ? array( 'CLOSE' ) : array( 'BACK' );
$content->assign($strContentAssocs);

if (!$popup) {
  $main = new Template(TPLPATH.'frame.tpl');
  $strMainAssocs['menu'] = &$menu;
}
else {
  $main = new Template(TPLPATH.'popup_frame.tpl');
}
$strMainAssocs['title'] = 'Help with CoMa - Your Conference Manager';
$strMainAssocs['content'] = &$content;

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
