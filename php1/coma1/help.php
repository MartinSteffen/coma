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
  $toccontentassocs['&popup'] = ($popup ? '&amp;popup' : '');
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
  $toccontentassocs['&popup'] = ($popup ? '&amp;popup' : '');
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
 * chapter-02-01.tpl
 */
if (in_array('layout', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.1';
  $strArrayChapterAssocs['chapter-title'] = 'General layout of the pages in CoMa';
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
 * chapter-02-02.tpl
 */
if (in_array('login', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.2';
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
  $strArrayRelatedAssocs['&popup'] = ($popup ? '&amp;popup' : '');
  $strArrayRelatedAssocs['linkname'] = 'registering for an account';
  $objRelatedTemplate->assign($strArrayRelatedAssocs);
  $objRelatedTemplate->parse();
  $strArrayChapterAssocs['related-link'] = $objRelatedTemplate->getOutput();
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
 * chapter-02-03.tpl
 */ 
if (in_array('register', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.3';
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
 * chapter-02-04.tpl
 */ 
if (in_array('imprint', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.4';
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
 * chapter-02-05.tpl
 */
if (in_array('profile', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.5';
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
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-05.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-05'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-06.tpl
 */
if ((in_array('main', $strArrayHelpTopics)) && (in_array('conferences', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.6';
  $strArrayChapterAssocs['chapter-title'] = 'The conferences overview page';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the conferences overwiew page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-06.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-06'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-07.tpl
 */
if ((in_array('create', $strArrayHelpTopics)) && (in_array('conference', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.7';
  $strArrayChapterAssocs['chapter-title'] = 'The create conference page / creating your own conference';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the create conference page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-07.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-07'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-07-01.tpl
 */
if ((in_array('create', $strArrayHelpTopics)) && (in_array('conference', $strArrayHelpTopics)) && (in_array('advanced', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.7.1';
  $strArrayChapterAssocs['chapter-title'] = 'The create conference advanced page / advanced settings for your conference';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the create conference page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-07-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-07-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-08.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('start', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.8';
  $strArrayChapterAssocs['chapter-title'] = "The chair's main overview";
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = "In the main window, you are currently viewing the chair's main overview page.";
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-08.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-08'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-09.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('users', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.9';
  $strArrayChapterAssocs['chapter-title'] = 'The manage users page / adding and removing roles and other attributes of users';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the users management page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-09.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-09'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-09-01.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('prefers', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.9.1';
  $strArrayChapterAssocs['chapter-title'] = "The view preferences page / viewing a reviewers' paper preferences";
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = "In the main window, you are currently viewing the reviewers' preferences page.";
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-09-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-09-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-10.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('papers', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.5';
  $strArrayChapterAssocs['chapter-title'] = 'The manage papers page / adding, removing, accepting and rejecting papers';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the paper management page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-10.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-10'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-10-01.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('paperreviews', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.10.1';
  $strArrayChapterAssocs['chapter-title'] = 'The view review report page / checking the review report of a paper';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the review report page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-10-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-10-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-11.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('reviews', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.11';
  $strArrayChapterAssocs['chapter-title'] = 'The manage reviews page / distributing reviewers among the papers';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the review management page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-11.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-11'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-11-01.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('reviewsreviewer', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.11.1';
  $strArrayChapterAssocs['chapter-title'] = 'The edit reviewers page / editing the list of reviewers';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the edit reviewers page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-11-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-11-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-12.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('confconfig', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.12';
  $strArrayChapterAssocs['chapter-title'] = 'The conference configuration page / setting the important values for your conference';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the conference configuration page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-12.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-12'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-12-01.tpl
 */
if ((in_array('chair', $strArrayHelpTopics)) && (in_array('confconfig', $strArrayHelpTopics)) && (in_array('advanced', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.12.1';
  $strArrayChapterAssocs['chapter-title'] = 'The advanced conference configuration page / setting the even more important values for your conference';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the conference configuration page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-12-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-12-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-13.tpl
 */
if ((in_array('reviewer', $strArrayHelpTopics)) && (in_array('start', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.13';
  $strArrayChapterAssocs['chapter-title'] = "The reviewer's main overview";
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = "In the main window, you are currently viewing the reviewer's main overview page.";
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-13.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-13'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-14.tpl
 */
if ((in_array('reviewer', $strArrayHelpTopics)) && (in_array('reviews', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.14';
  $strArrayChapterAssocs['chapter-title'] = 'The review page / managing your reviews';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the review page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-14.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-14'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-14-01.tpl
 */
if ((in_array('reviewer', $strArrayHelpTopics)) && (in_array('editreview', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.14.1';
  $strArrayChapterAssocs['chapter-title'] = 'The edit review page / editing your review';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the edit review page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-14-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-14-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-14-02.tpl
 */
if ((in_array('reviewer', $strArrayHelpTopics)) && (in_array('createreview', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.14.2';
  $strArrayChapterAssocs['chapter-title'] = 'The create review page / creating your review';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the create review page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-14-02.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-14-02'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-15.tpl
 */
if ((in_array('reviewer', $strArrayHelpTopics)) && (in_array('prefers', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.15';
  $strArrayChapterAssocs['chapter-title'] = 'The paper preferences page / managing your paper preferences';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the paper preferences page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-15.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-15'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-16.tpl
 */
if ((in_array('author', $strArrayHelpTopics)) && (in_array('start', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.16';
  $strArrayChapterAssocs['chapter-title'] = "The author's main overview";
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = "In the main window, you are currently viewing the author's main overview page.";
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-16.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-16'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-17.tpl
 */
if ((in_array('author', $strArrayHelpTopics)) && (in_array('papers', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.14';
  $strArrayChapterAssocs['chapter-title'] = "The author's paper management page / managing your papers";
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = "In the main window, you are currently viewing the author's paper management page.";
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-17.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-17'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-17-01.tpl
 */
if ((in_array('author', $strArrayHelpTopics)) && (in_array('createpaper', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.17.1';
  $strArrayChapterAssocs['chapter-title'] = 'The create paper page / uploading a paper to CoMa';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the create paper page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-17-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-17-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-18.tpl
 */
if ((in_array('participant', $strArrayHelpTopics)) && (in_array('start', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.18';
  $strArrayChapterAssocs['chapter-title'] = "The participant's main overview page";
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = "In the main window, you are currently viewing the participant's main overview page.";
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-18.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-18'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-19.tpl
 */
if ((in_array('participant', $strArrayHelpTopics)) && (in_array('settings', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.19';
  $strArrayChapterAssocs['chapter-title'] = 'The participation page / managing your participation';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the participation settings page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-19.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-19'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-20.tpl
 */
if ((in_array('user', $strArrayHelpTopics)) && (in_array('conference', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.20';
  $strArrayChapterAssocs['chapter-title'] = 'The conference information page';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the conference information page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-20.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-20'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-21.tpl
 */
if ((in_array('user', $strArrayHelpTopics)) && (in_array('users', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.21';
  $strArrayChapterAssocs['chapter-title'] = 'The list of chairs';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the list of chairs page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-21.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-21'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-22.tpl
 */
if ((in_array('user', $strArrayHelpTopics)) && (in_array('papers', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.22';
  $strArrayChapterAssocs['chapter-title'] = 'The list of accepted papers';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the list of accepted papers page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-22.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-22'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-23.tpl
 */
if (in_array('forum', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.23';
  $strArrayChapterAssocs['chapter-title'] = 'The forums / taking part in the discussions with other CoMa users';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the forum page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-23.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-23'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-24.tpl
 */
if (in_array('help', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.24';
  $strArrayChapterAssocs['chapter-title'] = 'The help page / getting help with CoMa';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the help page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-24.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-24'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-25.tpl
 */
if (in_array('detailpages', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.25';
  $strArrayChapterAssocs['chapter-title'] = 'The details pages / detailed information about papers or users';
  $strArrayChapterAssocs['related-topics'] = '';
  $strArrayChapterAssocs['special'] = '';
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-25.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-25'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-25-01.tpl
 */
if ((in_array('user', $strArrayHelpTopics)) && (in_array('userdetails', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.25.1';
  $strArrayChapterAssocs['chapter-title'] = 'The user details page / more information about a user';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the users detail page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-25-01.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-25-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-25-02.tpl
 */
if ((in_array('user', $strArrayHelpTopics)) && (in_array('paperdetails', $strArrayHelpTopics))){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.25.2';
  $strArrayChapterAssocs['chapter-title'] = 'The paper details page / more information about a paper';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the papers detail page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-25-02.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-25-02'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-02-26.tpl
 */
if (in_array('error', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '2.26';
  $strArrayChapterAssocs['chapter-title'] = 'The error page / when something blows up...';
  $strArrayChapterAssocs['related-topics'] = '';
  if ($i){
    $strArrayChapterAssocs['special'] = 'In the main window, you are currently viewing the error page.';
  }
  else{
    $strArrayChapterAssocs['special'] = '';
  }
  if (!empty($strArrayChapterAssocs['special'])){
    $strArrayChapterAssocs['if'] = array(1);
  }
  $strArrayChapterAssocs['related-link'] = '';
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-02-26.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-02-26'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-01.tpl
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
 * chapter-01-01.tpl
 */ 
if (in_array('step', $strArrayHelpTopics) && in_array('chair', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '1.1';
  $strArrayChapterAssocs['chapter-title'] = 'How to create a conferce';
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
 * chapter-01-02.tpl
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

/**
 * chapter-01-03.tpl
 */ 
if (in_array('step', $strArrayHelpTopics) && in_array('author', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '1.3';
  $strArrayChapterAssocs['chapter-title'] = 'How to submit your papers';
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
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-01-03.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $objChapterContent->assign($strArrayContentAssocs);
  $objChapterContent->parse();
  $strArrayChapterAssocs['content'] = $objChapterContent->getOutput();
  $objChaptertemplate->assign($strArrayChapterAssocs);
  $objChaptertemplate->parse();
  $strContentAssocs['chapter-01'] = $objChaptertemplate->getOutput();
}

/**
 * chapter-01-03.tpl
 */ 
if (in_array('step', $strArrayHelpTopics) && in_array('participant', $strArrayHelpTopics)){
  $objChaptertemplate = new Template(TPLPATH.'help_chapter.tpl');
  $strArrayChapterAssocs = defaultAssocArray();
  $strArrayChapterAssocs['chapter-no'] = '1.4';
  $strArrayChapterAssocs['chapter-title'] = 'How to get information for participation at a conference ';
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
  $objChapterContent = new Template(TPLPATH.'helptext/chapter-01-04.tpl');
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
