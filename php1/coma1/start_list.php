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
$strMainAssocs['content'] =  '<h2 align="center"> Liste aller Konferenzen </h2>';
$strMenueAssocs['loginName'] = $_SESSION['uname'];


/**
  * Anlegen der Tabelle mir allen Konferenzen
  */
$tabKopf = '
<div align= "center"> 
<table width="750" class="list"> 
  <tr> 
     <td class="listhead"> Konferenzname </td>  
     <td class="listhead"> Login </td>  
  </tr>
';

/** Lesen der Konferenzen und Rollen aus der Datenbank */
$conferences = $myDBAccess->getAllConferences();
$id = $myDBAccess->getPersonIdByEmail('rr@hase.de');

$zeilen =''; //die Zeilen der Tabelle
for ($i = 0; $i < count($conferences); $i++) {
  $person = $myDBAccess->getRoles($id,$conferences[$i]->intId);
  $cname = (string) $conferences[$i]->strName; 
  $zeilen = $zeilen.'<tr> <td class="z1" >'.$cname.'</td> <td>';
  for ($j = 0; $j < count($person); $j++){
    $zeilen = $zeilen.'&nbsp; <a href="'.strtolower($person[$j]).'.php">'.$person[$j].'</a> &nbsp;'; 
  } 
  $zeilen = $zeilen.'</td> </tr>'; 
 }
$tabEnde = '</table> </div> ';


$strMainAssocs['body'] = $tabKopf.$zeilen.$tabEnde;



/*
'
;
*/


/* $strMainAssocs['body'] =
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

*/
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;

$menue->assign(defaultAssocArray());
$submenue->assign(defaultAssocArray());
$mainPage->assign($strMainAssocs);
$menue->assign($strMenueAssocs);

$mainPage->parse();
$mainPage->output();

?>