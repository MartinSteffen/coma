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

if (!isset($GET_[reviewid])) {
  redirect("reviewer_reviews.php");
}

$content = new Template(TPLPATH.'reviewer_editreview.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$actMenu = REVIEWER;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Edit review #' + $_GET('reviewid');
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Reviewer  |  Reviews';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>