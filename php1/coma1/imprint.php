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

if (checkLogin()) {
  redirect('index.php');
}

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(4);
$menu->assign($strMenuAssocs);

$content = new Template(TPLPATH.'imprint.tpl');
$content->assign(defaultAssocArray());

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Imprint';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = 'CoMa  |  Imprint';


$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>