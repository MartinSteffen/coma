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

$content = new Template(TPLPATH.'create_conference.tpl');
$strContentAssocs = defaultAssocArray();
$ifArray = array();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
// siehe vond er Struktur her main_profile.php
if (isset($_POST['action'])) {
  $strContentAssocs['name']             = encodeText($_POST['name']);
  $strContentAssocs['description']      = encodeText($_POST['description']);
  $strContentAssocs['homepage']         = encodeURL($_POST['homepage']);
  $strContentAssocs['start_date']       = encodeText($_POST['start_date']);
  $strContentAssocs['end_date']         = encodeText($_POST['end_date']);
  $strContentAssocs['abstract_dl']      = encodeText($_POST['abstract_dl']);
  $strContentAssocs['paper_dl']         = encodeText($_POST['paper_dl']);
  $strContentAssocs['review_dl']        = encodeText($_POST['review_dl']);
  $strContentAssocs['final_dl']         = encodeText($_POST['final_dl']);
  $strContentAssocs['notification']     = encodeText($_POST['notification']);
  $strContentAssocs['min_reviews']      = encodeText($_POST['min_reviews']);
  $strContentAssocs['def_reviews']      = encodeText($_POST['def_reviews']);
  $strContentAssocs['min_papers']       = encodeText($_POST['min_papers']);
  $strContentAssocs['max_papers']       = encodeText($_POST['max_papers']);
  $strContentAssocs['variance']         = encodeText($_POST['variance']);
  $strTopics        = encodeTextArray($_POST['topics']);
  $strCriterions    = encodeTextArray($_POST['criterions']);
  $strCritDescripts = encodeTextArray($_POST['crit_descr']);
  $strCritMaxVals   = encodeTextArray($_POST['crit_max']);
  $strCritWeights   = encodeTextArray($_POST['crit_weight']);
  if (isset($_POST['advanced'])) {
    $intTopicNum = count($strTopics);    
    $intCritNum = count($strCriterions);
    $strTopics = array();
    $strCriterions    = array();
    $strCritDescripts = array();
    $strCritMaxVals   = array();
    $strCritWeights   = array();
    for ($i = 0; $i < $intTopicNum; $i++) {      
      if (!isset($_POST['del_topic-'.($i+1)])) {
        $strTopics[$i] = encodeText($_POST['topic_name-'.($i+1)]);
      }
    }     
    for ($i = 1; $i < $intCritNum; $i++) {
      if (!isset($_POST['del_crit-'.($i+1)])) {
        $strCriterions[]    = encodeText($_POST['crit_name-'.($i+1)]);
        $strCritDescripts[] = encodeText($_POST['crit_descr-'.($i+1)]);
        $strCritMaxVals[]   = encodeText($_POST['crit_max-'.($i+1)]);
        $strCritWeights[]   = encodeText($_POST['crit_weight-'.($i+1)]);      
      }
    } 
    /* 	
    if (isset($_POST['add_topic']) && !empty(encodeText($_POST['topic_name']))) {
      $strTopics[] = encodeText($_POST['topic_name']);
    }
    if (isset($_POST['add_crit']) && !empty(encodeText($_POST['crit_name']))) {
      $strCriterions[]    = encodeText($_POST['crit_name']);
      $strCritDescripts[] = encodeText($_POST['crit_descr']);
      $strCritMaxVals[]   = encodeText($_POST['crit_max']);
      $strCritWeights[]   = encodeText($_POST['crit_weight']);
    } 
    */  
  }
  if ( isset($_POST['adv_config']) ||
      (isset($_POST['advanced']) && !isset($_POST['simple_setup']))) {
    $content = new Template(TPLPATH.'create_conference_ext.tpl');
    $strContentAssocs['topic_lines'] = '';
    $strContentAssocs['crit_lines'] = '';
    for ($i = 0; $i < count($strTopics); $i++) {
      $topicForm = new Template(TPLPATH.'topic_listitem.tpl');
      $strTopicAssocs = defaultAssocArray();
      $strTopicAssocs['topic_no'] = $i;
      $strTopicAssocs['topic_name'] = $strTopics[$i];
      $topicForm->assign($strTopicAssocs);
      $topicForm->parse();
      $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
    }
    for ($i = 0; $i < count($strCriterions); $i++) {
      $critForm = new Template(TPLPATH.'criterion_listitem.tpl');
      $strCritAssocs = defaultAssocArray();
      $strCritAssocs['crit_no'] = $i;
      $strCritAssocs['crit_name']   = $strCriterions[$i];
      $strCritAssocs['crit_descr']  = $strCritDescripts[$i];
      $strCritAssocs['crit_max']    = $strCritMaxVals[$i];
      $strCritAssocs['crit_weight'] = $strCritWeights[$i];
      $critForm->assign($strCritAssocs);
      $critForm->parse();
      $strContentAssocs['crit_lines'] .= $critForm->getOutput();
    }    
  }
  $strContentAssocs['topics']         = '';
  $strContentAssocs['criterions']     = '';
  $strContentAssocs['crit_max']       = '';
  $strContentAssocs['crit_descr']     = '';
  $fltContentAssocs['crit_weight']    = '';
  $strContentAssocs['num_topics']     = count($strTopics);
  $strContentAssocs['num_criterions'] = count($strCriterions);
  for ($i = 0; $i < count($strTopics); $i++) {
    $strContentAssocs['topics'] .= (($i > 0) ? '|' : '') . encodeText($strTopics[$i]);
  } 
  for ($i = 0; $i < count($strCriterions); $i++) {
    $strContentAssocs['criterions']  .= (($i > 0) ? '|' : '').encodeText($strCriterions[$i]);
    $strContentAssocs['crit_descr']  .= (($i > 0) ? '|' : '').encodeText($strCritDescripts[$i]);
    $strContentAssocs['crit_max']    .= (($i > 0) ? '|' : '').encodeText($strCritMaxVals[$i]);
    $strContentAssocs['crit_weight'] .= (($i > 0) ? '|' : '').encodeText($strCritWeights[$i]);
  }
  $strContentAssocs['auto_numreviewer'] = encodeText($_POST['auto_numreviewer']);
  if (isset($_POST['auto_actaccount']) && !empty($_POST['auto_actaccount'])) {
    $ifArray[] = 2;
  }
  if (isset($_POST['auto_paperforum']) && !empty($_POST['auto_paperforum'])) {
    $ifArray[] = 3;
  }
  if (isset($_POST['auto_addreviewer']) && !empty($_POST['auto_addreviewer'])) {
    $ifArray[] = 4;
  }
  // Anlegen der Konferenz in der Datenbank
  if (isset($_POST['submit'])) {

    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (empty($_POST['name'])) {
      $strMessage = 'You have to fill in the field <b>Title</b>!';
    }
    // Versuche die neue Konferenz einzutragen
    else {      
      $result = $myDBAccess->addConference(encodeText($_POST['name']),                                           
                                           encodeURL($_POST['homepage']),
                                           encodeText($_POST['description']),                                           
                                           encodeText($_POST['abstract_dl']),
                                           encodeText($_POST['paper_dl']),
                                           encodeText($_POST['review_dl']),
                                           encodeText($_POST['final_dl']),
                                           encodeText($_POST['notification']),
                                           encodeText($_POST['start_date']),
                                           encodeText($_POST['end_date']),                                           
                                           encodeText($_POST['min_reviews']),
                                           encodeText($_POST['def_reviews']),
                                           encodeText($_POST['min_papers']),
                                           encodeText($_POST['max_papers']),
                                           encodeText($_POST['variance']),
                                           (!empty($_POST['auto_actaccount']) ? '1' : '0'),
                                           (!empty($_POST['auto_paperforum']) ? '1' : '0'),
                                           (!empty($_POST['auto_addreviewer']) ? '1' : '0'),
                                           encodeText($_POST['auto_numreviewer']),                                           
                                           $strTopics, $strCriterions, $strCritDescripts,
                                           $intCritMaxVals, $fltCritWeights);                                           
      if (!empty($result)) {
        // Erfolg (also anderes Template)
        $content = new Template(TPLPATH.'confirm_conference.tpl');
      }
      else if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during creating conference.', $myDBAccess->getLastError());
      }
    }
  }
}
// Wenn keine Daten geliefert worden, nimm die Defaultwerte
else {
  $ifArray[] = 2;
  $ifArray[] = 3;  
  $strContentAssocs['min_papers']       = '1';
  $strContentAssocs['max_papers']       = '100';
  $strContentAssocs['min_reviews']      = '3';
  $strContentAssocs['def_reviews']      = '4';
  $strContentAssocs['auto_numreviewer'] = '2';
  $strContentAssocs['variance']         = '0.5';
  $strContentAssocs['num_topics']       = '3';
  $strContentAssocs['num_criterions']   = '2';
  $strContentAssocs['topics']           = 'Informatics|Mathematics|Electronics';
  $strContentAssocs['criterions']       = 'Content|Form';
  $strContentAssocs['crit_max']         = '5|5';
  $strContentAssocs['crit_descr']       = 'Aspects of the content|Aspects of the outer form';
  $strContentAssocs['crit_weight']      = '0.7|0.3';
}

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 1;
}

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(3);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Create new Conference';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = 'CoMa  |  Create conference';

$main->assign($strMainAssocs);
$main->parse();
$main->output();
?>