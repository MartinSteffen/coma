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

// SID und basepath in Links einfügen
$links = defaultAssocArray();

$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] =  '<h2 align="center"> Liste der  Konferenzen bei denen Sie bereits 
                              angemeldet sind </h2>';
$strMenueAssocs['loginName'] = $_SESSION['uname'];


/**
  * Anlegen der Tabelle mit Konferenzen mit Mindeststatus Teilnehmer
  */
$tabKopf = '
  <div align= "center"> 
  <table width="750" class="list"> 
    <tr> 
       <td class="listhead"> Konferenzname </td>  
       <td class="listhead"> Login </td>  
    </tr>
   ';

//Lesen der Konferenzen und Rollen aus der Datenbank
$conferences = $myDBAccess->getAllConferences();
$id = $myDBAccess->getPersonIdByEmail($_SESSION['uname']);

$zeilen =''; //die Zeilen der Tabelle
for ($i = 0; $i < count($conferences); $i++) {
  $roles = $myDBAccess->getRoles($id,$conferences[$i]->intId);
  $cname = (string) $conferences[$i]->strName; 
  if (count($roles)!=NULL){
    $zeilen = $zeilen.'<tr> <td class="z1" >'.$cname.'</td> <td align="left">';
    // lese alle Rollen aus
    for ($j = 0; $j < count($roles); $j++){
      $zeilen = $zeilen.'&nbsp; <a href="'.$links['basepath'].strtolower($roles[$j]).'.php'.
      $links['SID'].'">'.$roles[$j].'</a> &nbsp;'; 
    } 
    $zeilen = $zeilen.'</td> </tr>'; 
  }

}

$tabEnde = '</table> </div> ';

$strMainAssocs['body'] = $tabKopf.$zeilen.$tabEnde;
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;

$menue->assign(defaultAssocArray());
$submenue->assign(defaultAssocArray());
$mainPage->assign($strMainAssocs);
$menue->assign($strMenueAssocs);

$mainPage->parse();
$mainPage->output();






?>