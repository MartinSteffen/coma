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
    $intCritNum  = count($strCriterions);
    $strTopics        = array();
    $strCriterions    = array();
    $strCritDescripts = array();
    $strCritMaxVals   = array();
    $strCritWeights   = array();
    for ($i = 0; $i < $intTopicNum; $i++) {
      if (!isset($_POST['del_topic-'.($i+1)])) {
        $strTopics[] = encodeText($_POST['topic_name-'.($i+1)]);
      }
    }
    for ($i = 0; $i < $intCritNum; $i++) {
      if (!isset($_POST['del_crit-'.($i+1)])) {
        $strCriterions[]    = encodeText($_POST['crit_name-'.($i+1)]);
        $strCritDescripts[] = encodeText($_POST['crit_descr-'.($i+1)]);
        $strCritMaxVals[]   = encodeText($_POST['crit_max-'.($i+1)]);
        $strCritWeights[]   = encodeText($_POST['crit_weight-'.($i+1)]);
      }
    }
    if (isset($_POST['add_topic']) && !empty($_POST['topic_name'])) {
        $strTopics[] = encodeText($_POST['topic_name']);
    }
    if (isset($_POST['add_crit']) && !empty($_POST['crit_name'])) {
      $strCriterions[]    = encodeText($_POST['crit_name']);
      $strCritDescripts[] = encodeText($_POST['crit_descr']);
      $strCritMaxVals[]   = encodeText($_POST['crit_max']);
      $strCritWeights[]   = encodeText($_POST['crit_weight']);
    }
  }
  if ( isset($_POST['adv_config'])    || (isset($_POST['advanced']) &&
      !isset($_POST['simple_config']) &&  !isset($_POST['submit']))) {
    $content = new Template(TPLPATH.'create_conference_ext.tpl');
    $strContentAssocs['topic_lines'] = '';
    $strContentAssocs['crit_lines']  = '';
    for ($i = 0; $i < count($strTopics); $i++) {
      $topicForm = new Template(TPLPATH.'topic_listitem.tpl');
      $strTopicAssocs = defaultAssocArray();
      $strTopicAssocs['topic_no']   = encodeText($i+1);
      $strTopicAssocs['topic_name'] = encodeText($strTopics[$i]);
      $topicForm->assign($strTopicAssocs);
      $topicForm->parse();
      $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
    }
    for ($i = 0; $i < count($strCriterions); $i++) {
      $critForm = new Template(TPLPATH.'criterion_listitem.tpl');
      $strCritAssocs = defaultAssocArray();
      $strCritAssocs['crit_no']     = encodeText($i+1);
      $strCritAssocs['crit_name']   = encodeText($strCriterions[$i]);
      $strCritAssocs['crit_descr']  = encodeText($strCritDescripts[$i]);
      $strCritAssocs['crit_max']    = encodeText($strCritMaxVals[$i]);
      $strCritAssocs['crit_weight'] = encodeText($strCritWeights[$i]);
      $critForm->assign($strCritAssocs);
      $critForm->parse();
      $strContentAssocs['crit_lines'] .= $critForm->getOutput();
    }
  }
  $strContentAssocs['topics']         = '';
  $strContentAssocs['criterions']     = '';
  $strContentAssocs['crit_max']       = '';
  $strContentAssocs['crit_descr']     = '';
  $strContentAssocs['crit_weight']    = '';
  $strContentAssocs['num_topics']     = encodeText(count($strTopics));
  $strContentAssocs['num_criterions'] = encodeText(count($strCriterions));
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

    /**
     *   Fehler bei der Eingabe abfangen
     *   noErrors = true, solange kein Fehler gefunden wurde
     */
    $noErrors = true; 
    if (empty($_POST['name'])        || empty($_POST['start_date']) ||
        empty($_POST['abstract_dl']) || empty($_POST['paper_dl'])   || 
        empty($_POST['review_dl'])   || empty($_POST['final_dl'])) {
      $strMessage = 'You have to fill in the fields <b>Title</b>, <b>Start date</b>, '.
                    'and the <b>Deadline</b> fields!';
      $noErrors = false;
    }
    // Test, ob das Datumsformat stimmt
    $abstract_submission_deadline_d= substr( $_POST['abstract_dl'],0,2);
    $abstract_submission_deadline_m= substr( $_POST['abstract_dl'],3,2);
    $abstract_submission_deadline_y= substr( $_POST['abstract_dl'],6,4);
    $paper_submission_deadline_d   = substr( $_POST['paper_dl'],0,2);
    $paper_submission_deadline_m   = substr( $_POST['paper_dl'],3,2);
    $paper_submission_deadline_y   = substr( $_POST['paper_dl'],6,4);
    $review_deadline_d             = substr( $_POST['review_dl'],0,2);
    $review_deadline_m             = substr( $_POST['review_dl'],3,2);
    $review_deadline_y             = substr( $_POST['review_dl'],6,4);
    $final_version_deadline_d      = substr( $_POST['final_dl'],0,2);
    $final_version_deadline_m      = substr( $_POST['final_dl'],3,2);
    $final_version_deadline_y      = substr( $_POST['final_dl'],6,4);
    $notification_d                = substr( $_POST['notification'],0,2);
    $notification_m                = substr( $_POST['notification'],3,2);
    $notification_y                = substr( $_POST['notification'],6,4);
    $conference_start_d            = substr( $_POST['start_date'],0,2);
    $conference_start_m            = substr( $_POST['start_date'],3,2);
    $conference_start_y            = substr( $_POST['start_date'],6,4);
    $conference_end_d              = substr( $_POST['end_date'],0,2);
    $conference_end_m              = substr( $_POST['end_date'],3,2);
    $conference_end_y              = substr( $_POST['end_date'],6,4);

    if (!checkdate((int)$abstract_submission_deadline_m,
                   (int)$abstract_submission_deadline_d,
		   (int)$abstract_submission_deadline_y) && !empty($_POST['abstract_dl'])) {
      $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').
                    'Wrong format in field \'Deadline for abstracts\': '.
	            $_POST['abstract_dl'];
      $noErrors = false;
    }
    if (!checkdate((int)$paper_submission_deadline_m,
	           (int)$paper_submission_deadline_d,
		   (int)$paper_submission_deadline_y) && !empty($_POST['paper_dl'])) {
      $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').
                    'Wrong format in field \'Deadline for paper submission\': '.
	            $_POST['paper_dl'];
      $noErrors = false;
    }
    if (!checkdate((int)$review_deadline_m,
		   (int)$review_deadline_d,
      		   (int)$review_deadline_y) && !empty($_POST['review_dl'])) {
      $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').
                    'Wrong format in field \'Deadline for reviews\':'.
                    $_POST['review_dl'];
      $noErrors = false;
    }
    if (!checkdate((int)$final_version_deadline_m,
		   (int)$final_version_deadline_d,
		   (int)$final_version_deadline_y) && !empty($_POST['final_dl'])) {
     $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').
                   'Wrong format in field \'Deadline for final versions\': '.
                   $_POST['final_dl'];
     $noErrors = false;
    }

    if (!checkdate((int)$notification_m,
		   (int)$notification_d,
		   (int)$notification_y) && !empty($_POST['notification'])) {
     $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').
                   'Wrong format in field \'Notification date\': '.
                   $_POST['notification'];
     $noErrors = false;
    }

    if (!checkdate((int)$conference_start_m,
		   (int)$conference_start_d,
		   (int)$conference_start_y) && !empty($_POST['start_date'])) {
     $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').
                   'Wrong format in field \'Start date\': '.
                   $_POST['start_date'];
     $noErrors = false;
   }

   if (!checkdate((int)$conference_end_m,
		   (int)$conference_end_d,
		   (int)$conference_end_y) && !empty($_POST['end_date'])) {
    $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').		   	
                  'Wrong format in field \'End date\': '.
                  $_POST['end_date'];
    $noErrors = false;
  }

  if (!$noErrors) {
    $strMessage .= '<br>Please use the format <b>dd/mm/yyyy</b> for the date fields, '.
                   'for example <b>01/05/2005</b>!';
  }

  /**
   * Test, ob die Datumsangaben plausibel sind
   *
   */
  if ($noErrors &&
       !(( $abstract_submission_deadline_y.
           $abstract_submission_deadline_m.
           $abstract_submission_deadline_d
           <=
           $paper_submission_deadline_y.
           $paper_submission_deadline_m.
           $paper_submission_deadline_d
         )
         &&
         ( empty($_POST['notification']) ||
	 ( $paper_submission_deadline_y.
           $paper_submission_deadline_m.
           $paper_submission_deadline_d
           <=
           $notification_y.
           $notification_m.
           $notification_d
	 ))
         &&
         ( empty($_POST['notification']) ||
         ( $notification_y.
           $notification_m.
           $notification_d
           <=
           $conference_start_y.
           $conference_start_m.
           $conference_start_d
         ))
         &&
         ( empty($_POST['end_time']) ||
         ( $conference_start_y.
           $conference_start_m.
           $conference_start_d
           <=
           $conference_end_y.
           $conference_end_m.
           $conference_end_d
	 ))
         &&
         ( $paper_submission_deadline_y.
           $paper_submission_deadline_m.
           $paper_submission_deadline_d
	   <=
           $final_version_deadline_y.
           $final_version_deadline_m.
           $final_version_deadline_d
         )
         &&
         ( $final_version_deadline_y.
           $final_version_deadline_m.
           $final_version_deadline_d
           <=
           $conference_start_y.
           $conference_start_m.
           $conference_start_d
	  )
	 )) { //nur aufrufen, wenn hier auch der Fehler vorliegt!         
     $strMessage = (!empty($strMessage) ? $strMessage.'<br>' : '').         	
                   'There are contradictions in the given dates!<br>'.
                   'It should be: Start date &leq; End date,<br>'.
                   'Abstract deadline < Paper deadline < Final version deadline < Start date,<br>'.
                   'and: Paper deadline < Review deadline < Notification < Start date.'
     $noErrors = false;
   }

    // Versuche die neue Konferenz einzutragen, wenn die Eingaben nicht fehlerhaft sind
    if ($noErrors) {
      $result = $myDBAccess->addConference($_POST['name'],
                                           $_POST['homepage'],
                                           $_POST['description'],
                                           $_POST['abstract_dl'],
                                           $_POST['paper_dl'],
                                           $_POST['review_dl'],
                                           $_POST['final_dl'],
                                           $_POST['notification'],
                                           $_POST['start_date'],
                                           $_POST['end_date'],
                                           $_POST['min_reviews'],
                                           $_POST['def_reviews'],
                                           $_POST['min_papers'],
                                           $_POST['max_papers'],
                                           $_POST['variance'],
                                           (!empty($_POST['auto_actaccount']) ? '1' : '0'),
                                           (!empty($_POST['auto_paperforum']) ? '1' : '0'),
                                           (!empty($_POST['auto_addreviewer']) ? '1' : '0'),
                                           $_POST['auto_numreviewer'],
                                           $strTopics, $strCriterions, $strCritDescripts,
                                           $strCritMaxVals, $strCritWeights);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during creating conference.', $myDBAccess->getLastError());
      }
      else {
        // Erfolg (also anderes Template)
        $content = new Template(TPLPATH.'confirm_conference.tpl');
        $strContentAssocs['return_page'] = 'main_conferences.php';
        $objConference = new Conference(0, '', '', '', encodeText($_POST['start_date']),
                                        encodeText($_POST['end_date']));
        $strContentAssocs['date'] = $objConference->getDateString();
        $ifArray = array();
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
  $ifArray[] = 9;
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
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Create conference';

$main->assign($strMainAssocs);
$main->parse();
$main->output();
?>