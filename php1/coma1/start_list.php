<?php
/**
 * @version $Id: chair_konf.php 618 2004-12-10 12:18:41Z waller $
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);
require_once('./include/header.inc.php');

$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_start.tpl');
$submenue = new Template(TPLPATH.'nav_start_konf.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] =  'Liste aller Konferenzen';
$strMainAssocs['body'] =
'
<tr>
  <td> Konferenz A </td> <td> <a href="login.php"> login </a> </td> <td> (register) </td>
</tr>
<tr>
  <td> Konferenz B </td> <td> <a href="login.php"> login </a> </td> <td> (register) </td>
</tr>
';
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;

$menue->assign(defaultAssocArray());
$submenue->assign(defaultAssocArray());
$mainPage->assign($strMainAssocs);

$mainPage->parse();
$mainPage->output();

?>