<?php
/**
 * @version $Id$
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
<div align="center">
<table width="750" class="list">
<tr>
  <td class="listhead"> Konferenzname </td> 
  <td class="listhead"> Einloggen </td> 
  <td class="listhead"> Registrierung </td>
</tr>
<tr class="list">
  <td class="list"> Konferenz A </td> 
  <td class="list"> <a href="login.php" class="list"> login </a> </td> 
  <td class="list"> (register) </td>
</tr>
<tr class="list">
  <td class="list"> Konferenz B </td> 
  <td class="list"> <a href="login.php" class="list"> login </a> </td> 
  <td class="list"> (register) </td>
</tr>
</table>
</div>
';
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;

$menue->assign(defaultAssocArray());
$submenue->assign(defaultAssocArray());
$mainPage->assign($strMainAssocs);

$mainPage->parse();
$mainPage->output();

?>