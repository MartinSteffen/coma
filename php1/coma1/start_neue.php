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
$strMainAssocs['content'] =  'neue Konferenz erstellen ';
$strMainAssocs['body'] ='
<div align="center">
<table width="750">

<tr>   
  <td> Konferenztitel 
    <form action="{basepath}login.php" method="post">
        Konferenzname: <input type="text" name="name" size="30" maxlength="127" /><br />
        Homepage: <input type="text" name="homepage" size="30" maxlength="127" /><br />
        Beschreibung: <input type="text" name="description" size="30" maxlength="127" /><br />
        <BR>
        <BR>
        Verantwortlicher der Konferenz: 
        <BR>
        Email-Addresse: <input type="text" name="userMail" size="30" maxlength="127" /><br />
        Password: <input type="password" name="userPassword" size="30" maxlength="127" /><br />
        <input type="hidden" name="action" value="login" /><br />
        <input type="submit" name="submit" value="Login" /><br />
 
   </form>
</td>

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