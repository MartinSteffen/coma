<?php
/**
 * @version $Id: index_regi.php 1081 2004-12-31 01:50:16Z miesling $
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

$strMessage ='';
$rec = false; // wird true gesetzt, wenn das Formulardaten korrekt ausgefüllt wurde

if (isset($_POST['name'])){
  $rec=true;
  // Test, ob alle Pflichtfelder ausgefüllt wurden
  if ( $_POST['name']=='' 
       //$_POST['homepage']==''|
       //$_POST['description']==''|
       //$_POST['abstract_submission_deadline']==''|
       //$_POST['paper_submission_deadline']==''|
       //$_POST['review_deadline']==''|
       //$_POST['final_version_deadline']==''|
       //$_POST['notification']==''|
       //$_POST['conference_start']==''|
       //$_POST['conference_end']==''|
       //$_POST['min_reviews_per_paper']
     ){
     $strMessage =  $strMessage.'Bitte alle Felder ausfüllen !!! <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
  // Test, ob das Datumsformat stimmt
  if (!checkdate((int)$_POST['abstract_submission_deadline_m'],
		 (int)$_POST['abstract_submission_deadline_d'],
		 (int)$_POST['abstract_submission_deadline_y'])){
     $strMessage =  $strMessage.' abstrakte Deadline konnte nicht gelesen werden 
                                  - Datumsformat TTMMJJJJ <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
  if (!checkdate((int)$_POST['paper_submission_deadline_m'],
		 (int)$_POST['paper_submission_deadline_d'],
		 (int)$_POST['paper_submission_deadline_y'])){
     $strMessage =  $strMessage.' Deadline zum Einreichen von Paper konnte nicht gelesen werden 
                                  - Datumsformat TTMMJJJJ <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
  if (!checkdate((int)$_POST['review_deadline_m'],
      		 (int)$_POST['review_deadline_d'],
      		 (int)$_POST['review_deadline_y'])){
     $strMessage =  $strMessage.'Review Deadline ('.(int)$_POST['review_deadline_d'].
		    (int)$_POST['review_deadline_m'].(int)$_POST['review_deadline_y'].
                    ') konnte nicht gelesen werden - Datumsformat TTMMJJJJ <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
  if (!checkdate((int)$_POST['final_version_deadline_m'],
		 (int)$_POST['final_version_deadline_d'],
		 (int)$_POST['final_version_deadline_y'])){
     $strMessage =  $strMessage.'Final Version Deadline  konnte nicht gelesen werden 
                                 - Datumsformat TTMMJJJJ <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
  if (!checkdate((int)$_POST['conference_start_m'],
		 (int)$_POST['conference_start_d'],
		 (int)$_POST['conference_start_y'])){
     $strMessage =  $strMessage.' Konferenzstart konnte nicht gelesen werden 
                                  - Datumsformat TTMMJJJJ <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
  if (!checkdate((int)$_POST['conference_end_m'],
		 (int)$_POST['conference_end_d'],
		 (int)$_POST['conference_end_y'])){
     $strMessage =  $strMessage.' Konferenzende konnte nicht gelesen werden 
                                  - Datumsformat TTMMJJJJ <br>';
     $strMainAssocs['message'] = $strMessage; 
     $rec = false;
  }
}



/**
  *  Anlegen einer Konferenz in der Datenbank
  *  wenn alle Daten korrekt empfangen wurden
  */

if ($rec==true){
  $mainPage = new Template(TPLPATH.'main.tpl');
  $menue = new Template(TPLPATH.'nav_start.tpl');
  $loginPage = new Template(TPLPATH.'empty.tpl');

  $strMainAssocs = defaultAssocArray();
  $strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
  
  $strMainAssocs['content'] = ' ';
  $msg=$myDBAccess->addConference( $_POST['name'],
                                   $_POST['homepage'],
                                   $_POST['description'],
                                   $_POST['abstract_submission_deadline_y'].
                                   $_POST['abstract_submission_deadline_m'].  
                                   $_POST['abstract_submission_deadline_d'],
                                   $_POST['paper_submission_deadline_y'].
                                   $_POST['paper_submission_deadline_m'].
                                   $_POST['paper_submission_deadline_d'],
                                   $_POST['review_deadline_y'].
                                   $_POST['review_deadline_m'].
                                   $_POST['review_deadline_d'],
                                   $_POST['final_version_deadline_y'].
                                   $_POST['final_version_deadline_m'].
                                   $_POST['final_version_deadline_d'],
                                   $_POST['notification_y'].
                                   $_POST['notification_m'].
                                   $_POST['notification_d'],
                                   $_POST['conference_start_y'].
                                   $_POST['conference_start_m'].
                                   $_POST['conference_start_d'],
                                   $_POST['conference_end_y'].
                                   $_POST['conference_end_m'].
                                   $_POST['conference_end_d'],
                                   $_POST['min_reviews_per_paper'] );

    
    
  if ($msg == false){
    $strMessage = 'Ein Fehler beim Anlegen der Konferenz in der Datenbank ist aufgetreten ' ;
    $strMainAssocs['message'] = $strMessage; 
  }
  else {
    $strMessage = 'neue Konferenz'.$_POST['name'].' erfolgreich angelegt.';
    $strMainAssocs['message'] = $strMessage; 
  }
 
  $loginPage->assign($strMainAssocs);

  $strMainAssocs['body'] = & $loginPage;
  $strMainAssocs['menue'] =& $menue;
  $strMainAssocs['submenue'] = '';

  $mainPage->assign($strMainAssocs);
  $menue->assign(defaultAssocArray());
  $menue->assign($strMenueAssocs);
  $mainPage->parse();
  $mainPage->output();

  unset($_POST['name']);
 }

 // Wurde mit der Anforderung dieser Seite wurden keine oder fehlerhafte POST-Daten mitgeliefert,
 // dann wird das Registrierungsformular angezeigt.
 else {
   $mainPage = new Template(TPLPATH.'main.tpl');
   $menue = new Template(TPLPATH.'nav_start.tpl');
   $loginPage = new Template(TPLPATH.'neueKonferenz.tpl');
   $emptyPage = new Template(TPLPATH.'empty.tpl');

   $strMainAssocs = defaultAssocArray();
   $strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
   $strMainAssocs['content'] = '
   <h2 align="center"> Der Ersteller der Konferenz automatisch auch der Chair für diese Konferenz !!! </h2>';
   $strMainAssocs['body'] = & $loginPage;
   $strMainAssocs['menue'] =& $menue;
   $strMainAssocs['submenue'] = '';
   $strMenueAssocs['loginName'] = $_SESSION['uname'];


   $strLoginAssocs = defaultAssocArray();
   $strLoginAssocs['message'] = $strMessage;
   $mainPage->assign($strMainAssocs);
   $menue->assign(defaultAssocArray());
   $menue->assign($strMenueAssocs);

   $loginPage->assign($strLoginAssocs);
   $emptyPage->assign($strLoginAssocs);

   $mainPage->parse();
   $mainPage->output();
  }

?>
