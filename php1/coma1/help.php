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
$i = (isset($_GET['i'])) ? true : false;

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


/**
 * toc.tpl
 */ 
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


/**
 * chapther-02-01.tpl
 */ 
if (in_array('login', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.1';
  $strArrayChapterAssocs['chapter-title'] = 'The login page / logging into your account';
  $strArrayChapterAssocs['related-topics'] = 'related topics:';
  $strArrayChapterAssocs['special'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the login page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
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

/**
 * chapther-02-02.tpl
 */ 
if (in_array('register', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.2';
  $strArrayChapterAssocs['chapter-title'] = 'The registration page / signing up for your account';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the registration page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-02.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-02'] = $objChaptertemplate->getOutput();
}


/**
 * chapther-02-03.tpl
 */ 
if (in_array('imprint', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.3';
  $strArrayChapterAssocs['chapter-title'] = 'The imprint page / disclaimers and legal stuff';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the imprint page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-03.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-03'] = $objChaptertemplate->getOutput();
}

/**
 * chapther-02-04.tpl
 */ 
if (in_array('profile', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.4';
  $strArrayChapterAssocs['chapter-title'] = 'The profile page / modifying your profile';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the profile page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-04.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-04'] = $objChaptertemplate->getOutput();
}

/**
 * chapther-01.tpl
 */ 
if (in_array('introduction', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '1';
  $strArrayChapterAssocs['chapter-title'] = 'Welcome to the step by step introduction in Coma !';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the registration page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-01'] = $objChaptertemplate->getOutput();
}


/**
 * chapther-01-01.tpl
 */ 
if (in_array('step', $strArrayHelpTopics) && in_array('chair', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '1.1';
  $strArrayChapterAssocs['chapter-title'] = 'How to administrate a conferce';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the registration page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-01-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapther-01-02.tpl
 */ 
if (in_array('step', $strArrayHelpTopics) && in_array('reviewer', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '1.2';
  $strArrayChapterAssocs['chapter-title'] = 'How to review the papers';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the registration page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-01-02.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-01'] = $objChaptertemplate->getOutput();
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
