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

 
if (isset($_POST['anmeldung'])){
  $myDBAccess->addRole($_POST['anmeldung'],$myDBAccess->getPersonIdByEmail($_SESSION['uname']),0);
  unset($_POST['anmeldung']);

}

$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_start.tpl');
$submenue = new Template(TPLPATH.'nav_start_konf.tpl');

// SID und basepath in Links einf�gen
$links = defaultAssocArray();

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
$id = $myDBAccess->getPersonIdByEmail($_SESSION['uname']);

$zeilen =''; //die Zeilen der Tabelle
for ($i = 0; $i < count($conferences); $i++) {
  $roles = $myDBAccess->getRoles($id,$conferences[$i]->intId);
  $cname = (string) $conferences[$i]->strName; 
  $zeilen = $zeilen.'<tr> <td class="z1" >'.$cname.'</td> <td align="left">';
  if (count($roles)!=NULL){
    // lese alle Rollen aus
    for ($j = 0; $j < count($roles); $j++){
      $zeilen = $zeilen.'&nbsp; <a href="'.$links['basepath'].strtolower($roles[$j]).'.php'.
                $links['SID'].'">'.$roles[$j].'</a> &nbsp;'; 
    } 
    $zeilen = $zeilen.'</td> </tr>'; 
  }
  else {
    // falls Benutzer keine Rolle hat kann er sich als Teilnehmer anmelden
    $zeilen = $zeilen.
    ' <form action="'.$links['basepath'].'start_list.php'.$links['SID'].'" method="post">  
      <input type="hidden" name="anmeldung" value="'.$conferences[$i]->intId.'" /> 
      <input type="submit" name="submit" value="Anmeldung" /> 
      </form>
    ';
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