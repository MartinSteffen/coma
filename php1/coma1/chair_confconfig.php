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

$content = new Template(TPLPATH.'edit_conference.tpl');
$strContentAssocs = defaultAssocArray();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['action'])) {
  $strContentAssocs['name']         = $_POST['name'];
  $strContentAssocs['description']  = $_POST['description'];
  $strContentAssocs['homepage']     = $_POST['homepage'];
  $strContentAssocs['start_date']   = $_POST['start_date'];
  $strContentAssocs['end_date']     = $_POST['end_date'];
  $strContentAssocs['abstract_dl']  = $_POST['abstract_dl'];
  $strContentAssocs['paper_dl']     = $_POST['paper_dl'];
  $strContentAssocs['review_dl']    = $_POST['review_dl'];
  $strContentAssocs['final_dl']     = $_POST['final_dl'];
  $strContentAssocs['notification'] = $_POST['notification'];
  $strContentAssocs['min_reviews']  = $_POST['min_reviews'];
  $strContentAssocs['def_reviews']  = $_POST['def_reviews'];
  $strContentAssocs['min_papers']   = $_POST['min_papers'];
  $strContentAssocs['max_papers']   = $_POST['max_papers'];
  $strContentAssocs['variance']     = $_POST['variance'];
  $strContentAssocs['criteria']     = $_POST['criteria'];
  $strContentAssocs['topics']       = $_POST['topics'];
  $strContentAssocs['crit_max']     = $_POST['crit_max'];
  $strContentAssocs['crit_descr']   = $_POST['crit_descr'];
  $strContentAssocs['auto_actaccount']  =
   (isset($_POST['auto_actaccount']) ? $_POST['auto_actaccount'] : '');
  $strContentAssocs['auto_paperforum']  =
   (isset($_POST['auto_paperforum']) ? $_POST['auto_paperforum'] : '');
  $strContentAssocs['auto_addreviewer'] =
   (isset($_POST['auto_addreviewer']) ? $_POST['auto_addreviewer'] : '');
  $strContentAssocs['auto_numreviewer'] = $_POST['auto_numreviewer'];

  // Aktualisieren der Konferenz in der Datenbank
  if (isset($_POST['submit'])) {

    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (empty($_POST['name'])) {
      $strMessage = 'You have to fill in the field <b>Title</b>!';
    }
    // Versuche die Konferenz zu aktualisieren
    else {
      $result = false; // [TODO] Konferenz aktualisieren
      if (!empty($result)) {
        // Erfolg
        $strMessage = 'Conference setting was changed.';
      }
      else if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('updating conference', $myDBAccess->getLastError());
      }
    }
  }
  // Oeffnen der erweiterten Einstellungen
  else if (isset($_POST['adv_config'])) {
    $content = new Template(TPLPATH.'edit_conference_ext.tpl');
  }
}
// Wenn keine Daten geliefert worden, nimm die Defaultwerte
else {
  $strContentAssocs['auto_actaccount']  = 'checked';
  $strContentAssocs['auto_paperforum']  = 'checked';
  $strContentAssocs['auto_addreviewer'] = '';
  $strContentAssocs['auto_numreviewer'] = '2';
  $strContentAssocs['variance']         = '0.5';
}

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(1);
}

$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 5;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Configurate Conference';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Chair  |  Conference Config.';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>