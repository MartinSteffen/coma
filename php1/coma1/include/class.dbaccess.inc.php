<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}
if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}

require_once(INCPATH.'lib.inc.php');
require_once(INCPATH.'class.mysql.inc.php');

require_once(INCPATH.'class.conference.inc.php');
require_once(INCPATH.'class.conferencedetailed.inc.php');
require_once(INCPATH.'class.criterion.inc.php');
require_once(INCPATH.'class.errorhandling.inc.php');
require_once(INCPATH.'class.forum.inc.php');
require_once(INCPATH.'class.forumdetailed.inc.php');
require_once(INCPATH.'class.message.inc.php');
require_once(INCPATH.'class.paper.inc.php');
require_once(INCPATH.'class.paperdetailed.inc.php');
require_once(INCPATH.'class.papersimple.inc.php');
require_once(INCPATH.'class.person.inc.php');
require_once(INCPATH.'class.personalgorithmic.inc.php');
require_once(INCPATH.'class.persondetailed.inc.php');
require_once(INCPATH.'class.review.inc.php');
require_once(INCPATH.'class.reviewdetailed.inc.php');
require_once(INCPATH.'class.reviewerattitude.inc.php');
require_once(INCPATH.'class.topic.inc.php');

/**
 * Klasse DBAccess
 *
 * @author Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage DBAccess
 * @access protected
 */
class DBAccess extends ErrorHandling {
  /**#@+
   * @access private
   */
  /**
   * @var MySql
   */
  var $mySql;
  /**#@-*/

  /**
   * Konstruktor: Erzeugt eine Verbindung mit der Datenbank.
   *
   * @param MySql $mySql ein MySql-Objekt
   * @return bool true gdw. erfolgreich
   * @see error()
   * @see getLastError()
   */
  function DBAccess(&$mySql) {
    $this->mySql = &$mySql;
    return $this->success();
  }

  // ---------------------------------------------------------------------------
  // Definition der Selektoren
  // ---------------------------------------------------------------------------

  /**
   * Gibt eine Eindeutige geheime(!!) Information ueber einen Benutzer zurueck
   * Also das verschluesselte Passwort...
   *
   * @param int $intUId Die UserId
   * @return string Das Verschluesselte Passwort
   * @access public
   * @author Jan (01.02.05)
   */
  function getKeyOfPerson($intUId) {
    $s = sprintf("SELECT   password".
                 " FROM    Person".
                 " WHERE   id = '%d'",
                           s2db($intUId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getKeyOfPerson', $this->mySql->getLastError());
    }
    if (!empty($data)) {
      return $this->success($data[0]['password']);
    }
    return $this->success(false);
  }

 /**
   * Prueft, ob die Email-Adresse in der Datenbank gespeichert wurde.
   *
   * @return bool true gdw. die Email in der Datenbank gefunden wurde
   * @access public
   * @author Daniel (20.12.04)
   */
  function checkEmail($strEmail) {
    $s = sprintf("SELECT   email".
                 " FROM    Person".
                 " WHERE   email = '%s'",
                           s2db($strEmail));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('checkEmail', $this->mySql->getLastError());
    }
    if (!empty($data)) {
      return $this->success(true);
    }
    return $this->success(false);
  }

  /**
   * Prueft, ob die angegebenen User-Daten gueltig sind.
   *
   * @param string $strUserName Der zu ueberpruefende Benutzername
   * @param string $strPassword Das zu ueberpruefende Passwort (verschluesselt)
   * @return bool true gdw. die Daten in der Person-Tabelle hinterlegt sind
   * @access public
   * @author Tom (15.12.04)
   */
  function checkLogin($strUserName, $strPassword) {
    $s = sprintf("SELECT   email, password".
                 " FROM    Person".
                 " WHERE   email = '%s'".
                 " AND     password = '%s'",
                           s2db($strUserName),
                           s2db($strPassword));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('checkLogin', $this->mySql->getLastError());
    }
    if (!empty($data)) {
      return $this->success(true);
    }
    return $this->success(false);
  }

  /**
   * Liefert ein Array mit allen Konferenzen zurueck.
   *
   * @return Conference [] Gibt ein leeres Array zurueck, falls keine Konferenzen
   *                       existieren.
   * @access public
   * @author Daniel (29.12.04), Tom (12.01.05)
   */
  function getAllConferences() {
    $s = "SELECT  id, name, homepage, description, conference_start, conference_end".
        " FROM    Conference";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getAllConferences', $this->mySql->getLastError());
    }
    $objConferences = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $data[$i]['conference_start'] = emptyDBtime($data[$i]['conference_start']);
      $data[$i]['conference_end'] = emptyDBtime($data[$i]['conference_end']);
      $objConferences[] = (new Conference($data[$i]['id'], $data[$i]['name'],
                            $data[$i]['homepage'], $data[$i]['description'],
                            $data[$i]['conference_start'], $data[$i]['conference_end']));
    }
    return $this->success($objConferences);
  }

  /**
   * Liefert ein ConferenceDetailed-Objekt mit Konferenzdaten der Konferenz $intConferenceId
   * zurueck.
   *
   * @param int $intConferenceId Konferenz-ID
   * @return ConferenceDetailed [] Gibt ein false zurueck, falls die Konferenz nicht existiert.
   * @access public
   * @author Tom (08.01.05)
   */
  function getConferenceDetailed($intConferenceId) {
    $s = sprintf("SELECT   c.id, name, homepage, description, abstract_submission_deadline,".
                 "         paper_submission_deadline, review_deadline, final_version_deadline,".
                 "         notification, conference_start, conference_end, min_reviews_per_paper,".
                 "         default_reviews_per_paper, min_number_of_papers, max_number_of_papers,".
                 "         critical_variance, auto_activate_account, auto_open_paper_forum,".
                 "         auto_add_reviewers, number_of_auto_add_reviewers".
                 " FROM    Conference AS c".
                 " INNER   JOIN ConferenceConfig AS cc".
                 " ON      c.id = cc.id".
                 " WHERE   c.id = '%d'",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getConferenceDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $objCriterions = $this->getCriterionsOfConference($intConferenceId);
    if ($this->failed()) {
      return $this->error('getConferenceDetailed', $this->getLastError());
    }
    $objTopics = $this->getTopicsOfConference($intConferenceId);
    if ($this->failed()) {
      return $this->error('getConferenceDetailed', $this->getLastError());
    }
    $data[0]['conference_start'] = emptyDBtime($data[0]['conference_start']);
    $data[0]['conference_end'] = emptyDBtime($data[0]['conference_end']);
    $data[0]['abstract_submission_deadline'] = emptyDBtime($data[0]['abstract_submission_deadline']);
    $data[0]['paper_submission_deadline'] = emptyDBtime($data[0]['paper_submission_deadline']);
    $data[0]['review_deadline'] = emptyDBtime($data[0]['review_deadline']);
    $data[0]['final_version_deadline'] = emptyDBtime($data[0]['final_version_deadline']);
    $data[0]['notification'] = emptyDBtime($data[0]['notification']);
    $objConferenceDetailed =
      new ConferenceDetailed($data[0]['id'], $data[0]['name'],
                             $data[0]['homepage'], $data[0]['description'],
                             $data[0]['conference_start'], $data[0]['conference_end'],
                             $data[0]['abstract_submission_deadline'],
                             $data[0]['paper_submission_deadline'], $data[0]['review_deadline'],
                             $data[0]['final_version_deadline'], $data[0]['notification'],
                             $data[0]['min_reviews_per_paper'],
                             $data[0]['default_reviews_per_paper'],
                             $data[0]['min_number_of_papers'], $data[0]['max_number_of_papers'],
                             $data[0]['critical_variance'],
                             db2b($data[0]['auto_activate_account']),
                             db2b($data[0]['auto_open_paper_forum']),
                             db2b($data[0]['auto_add_reviewers']),
                             $data[0]['number_of_auto_add_reviewers'], $objCriterions,
                             $objTopics);
    return $this->success($objConferenceDetailed);
  }

  /**
   * Liefert ein Array von Criterion-Objekten zurueck, die Bewertungskriterien
   * der Konferenz $intConferenceId sind.
   *
   * @param int $intConferenceId Die ID der zu pruefenden Konferenz.
   * @return Criterion [] Gibt ein leeres Array zurueck, falls fuer die Konferenz keine
   *                      Bewertungskriterien definiert sind.
   * @access public
   * @author Sandro (18.12.04)
   */
  function getCriterionsOfConference($intConferenceId) {
    $s = sprintf("SELECT   id, name, description, max_value, quality_rating".
                 " FROM    Criterion".
                 " WHERE   conference_id = '%d'",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getCriterionsOfConference', $this->mySql->getLastError());
    }
    $objCriterions = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $fltWeight = $data[$i]['quality_rating'] / 100.0;
      $objCriterions[] = (new Criterion($data[$i]['id'], $data[$i]['name'],
                            $data[$i]['description'], $data[$i]['max_value'], $fltWeight));
    }
    return $this->success($objCriterions);
  }

  /**
   * Liefert ein Array von Topic-Objekten zurueck, die als Topics der
   * Konferenz $intConferenceId definiert sind.
   *
   * @return Topic [] Gibt ein leeres Array zurueck, falls fuer die Konferenz keine
   *                  Topics definiert sind.
   * @access public
   * @author Sandro (18.12.04)
   */
  function getTopicsOfConference($intConferenceId) {
    $s = sprintf("SELECT   id, name".
                 " FROM    Topic".
                 " WHERE   conference_id = '%d'",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getTopicsOfConference', $this->mySql->getLastError());
    }
    $objTopics = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objTopics[] = (new Topic($data[$i]['id'], $data[$i]['name']));
    }
    return $this->success($objTopics);
  }

  /**
   * Liefert die ID der Person, deren E-Mail-Adresse $strEmail ist.
   *
   * @param string $strEmail E-Mail-Adresse der Person.
   * @return int false, falls keine Person mit E-Mail-Adresse $strEmail gefunden wurde.
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
   */
  function getPersonIdByEmail($strEmail) {
    $s = sprintf("SELECT   id".
                 " FROM    Person".
                 " WHERE   email = '%s'",
                           s2db($strEmail));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPersonIdByEmail', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    return $this->success($data[0]['id']);
  }

  /**
   * Liefert ein Person-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId ID der Konferenz, zu der die Rollen der Person ermittelt
   *                             werden sollen. Optional: Ist $intConfereceId nicht
   *                             angegeben, werden keine Rollen fuer die Person ermittelt.
   * @return Person false, falls keine Person mit ID $intPersonId gefunden wurde.
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
   */
  function getPerson($intPersonId, $intConferenceId=false) {
    $s = sprintf("SELECT   id, first_name, last_name, email, title".
                 " FROM    Person".
                 " WHERE   id = '%d'",
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPerson', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $objPerson = (new Person($data[0]['id'], $data[0]['first_name'], $data[0]['last_name'],
                    $data[0]['email'], 0, $data[0]['title']));
    if (!empty($intConferenceId)) {
      $s = sprintf("SELECT   role_type, state".
                   " FROM    Role".
                   " WHERE   person_id = '%d'".
                   " AND     conference_id = '%d'",
                             s2db($data[0]['id']),
                             s2db($intConferenceId));
      $role_data = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getPerson', $this->mySql->getLastError());
      }
      for ($i = 0; $i < count($role_data) && !empty($role_data); $i++) {
        $objPerson->addRole($role_data[$i]['role_type'], $role_data[$i]['state']);
      }
    }
    return $this->success($objPerson);
  }

  /**
   * Liefert ein PersonAlgorithmic-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId ID der Konferenz, zu der die spezifischen Attribute
   *                             der Person geholt werden.
   * @return PersonAlgorithmic false, falls keine Person mit ID $intPersonId gefunden wurde.
   * @access public
   * @author Tom (18.01.05)
   */
  function getPersonAlgorithmic($intPersonId, $intConferenceId) {
    if (empty($intConferenceId)) {
      return $this->success(false);
    }
    // Basisdaten
    $s = sprintf("SELECT   id, first_name, last_name, email, title".
                 " FROM    Person".
                 " WHERE   id = '%d'",
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPersonAlgorithmic', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }

    $objPersonAlgorithmic = new PersonAlgorithmic($data[0]['id'], $data[0]['first_name'],
                             $data[0]['last_name'], $data[0]['email'], 0, $data[0]['title']);

    // konferenzspezifische Attribute
    $objPersonAlgorithmic->objPreferredTopics =
      $this->getPreferredTopics($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('getPersonAlgorithmic', $this->getLastError());
    }
    $objPersonAlgorithmic->objPreferredPapers =
      $this->getPreferredPapers($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('getPersonAlgorithmic', $this->getLastError());
    }
    $objPersonAlgorithmic->objDeniedPapers =
      $this->getDeniedPapers($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('getPersonAlgorithmic', $this->getLastError());
    }
    $objPersonAlgorithmic->objExcludedPapers =
      $this->getExcludedPapers($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('getPersonAlgorithmic', $this->getLastError());
    }

    // Rollen der Person
    $s = sprintf("SELECT   role_type, state".
                 " FROM    Role".
                 " WHERE   person_id = '%d'".
                 " AND     conference_id = '%d'",
                           s2db($data[0]['id']),
                           s2db($intConferenceId));
    $role_data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPersonAlgorithmic', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($role_data) && !empty($role_data); $i++) {
      $objPersonAlgorithmic->addRole($role_data[$i]['role_type'], $role_data[$i]['state']);
    }

    return $this->success($objPersonAlgorithmic);
  }

  /**
   * Liefert ein PersonDetailed-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId ID der Konferenz, zu der die Rollen der Person ermittelt
   *                             werden sollen. Optional: ist $intConferenceId nicht
   *                             angegeben, werden keine Rollen fuer die Person ermittelt.
   * @return PersonDetailed false, falls keine Person mit ID $intPersonId gefunden wurde.
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
   */
  function getPersonDetailed($intPersonId, $intConferenceId=false) {
    $s = sprintf("SELECT   id, first_name, last_name, email, title, affiliation,".
                 "         street, city, postal_code, state, country, phone_number, fax_number".
                 " FROM    Person".
                 " WHERE   id = '%d'",
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPersonDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $objPersonDetailed = (new PersonDetailed($data[0]['id'], $data[0]['first_name'],
                            $data[0]['last_name'], $data[0]['email'], 0,
                            $data[0]['title'], $data[0]['affiliation'], $data[0]['street'],
                            $data[0]['city'], $data[0]['postal_code'], $data[0]['state'],
                            $data[0]['country'], $data[0]['phone_number'],
                            $data[0]['fax_number']));
    if (!empty($intConferenceId)) {
      $s = sprintf("SELECT   role_type, state".
                   " FROM    Role".
                   " WHERE   person_id = '%d'".
                   " AND     conference_id = '%d'",
                             s2db($data[0]['id']),
                             s2db($intConferenceId));
      $role_data = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getPersonDetailed', $this->mySql->getLastError());
      }
      for ($i = 0; $i < count($role_data) && !empty($role_data); $i++) {
        $objPersonDetailed->addRole($role_data[$i]['role_type'], $role_data[$i]['state']);
      }
    }
    return $this->success($objPersonDetailed);
  }

  /**
   * Liefert ein Array von Person-Objekten zurueck, die Benutzer der
   * Konferenz $intConferenceId sind.
   *
   * @param int $intConferenceId ID der Konferenz
   * @param int $intOrder Gibt an, wonach sortiert werden soll (1=Name, 2=Email)
   * @return Person [] Ist im Regelfall nicht leer.
   * @access public
   * @author Sandro (19.01.05)
   * @todo Existenz der Konferenz muss noch geprueft werden.
   */
  function getUsersOfConference($intConferenceId, $intOrder=false) {
    $s = "SELECT  id, first_name, last_name, email, title".
         " FROM    Person";
    if (!empty($intOrder)) {
      if ($intOrder == 1) {
        $s .= " ORDER BY last_name";
      }
      else if ($intOrder == 2) {
        $s .= " ORDER BY email";
      }
    }
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getUsersOfConference', $this->mySql->getLastError());
    }
    $objPersons = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objPerson = (new Person($data[$i]['id'], $data[$i]['first_name'], $data[$i]['last_name'],
                      $data[$i]['email'], 0, $data[$i]['title']));
      $s = sprintf("SELECT   role_type, state".
                   " FROM    Role".
                   " WHERE   person_id = '%d'".
                   " AND     conference_id = '%d'",
                             s2db($data[$i]['id']),
                             s2db($intConferenceId));
      $role_data = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getUsersOfConference', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($role_data) && !empty($role_data); $j++) {
        $objPerson->addRole($role_data[$j]['role_type'], $role_data[$j]['state']);
      }
      if ($objPerson->hasAnyRole() || $objPerson->hasAnyRoleRequest()) {
        $objPersons[] = $objPerson;
      }
    }
    return $this->success($objPersons);
  }

  /**
   * Prueft, ob die Person $intPersonId in der Konferenz $intConferenceId die
   * Rolle $intRoleType besitzt.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId ID der Konferenz
   * @param int $intRoleType Wert der Rolle (CHAIR, AUTHOR, REVIEWER, PARTICIPANT),
   *                         0 = irgendeine Rolle
   * @return Person true gdw. die Person in der Konferenz die Rolle besitzt.
   * @access public
   * @author Sandro (27.01.05)
   */
  function hasRoleInConference($intPersonId, $intConferenceId, $intRoleType=0) {
    $s = ($intRoleType != 0 ? sprintf("SELECT   state".
                                      " FROM    Role".
                                      " WHERE   person_id = '%d'".
                                      " AND     conference_id = '%d'".
                                      " AND     role_type = '%d'".
                                      " AND     (state IS NULL OR state = '0')",
                                      s2db($intPersonId),
                                      s2db($intConferenceId),
                                      s2db($intRoleType))
                            : sprintf("SELECT   state".
                                      " FROM    Role".
                                      " WHERE   person_id = '%d'".
                                      " AND     conference_id = '%d'".
                                      " AND     (state IS NULL OR state = '0')",
                                     s2db($intPersonId),
                                     s2db($intConferenceId)));
    $role_data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('hasRoleInConference', $this->mySql->getLastError());
    }
    return $this->success(!empty($role_data));
  }

  /**
   * Liefert das PaperSimple-Objekt mit der ID $intPaperId zurueck.
   *
   * @param int $intPaperId ID des Papers
   * @return PaperSimple false, falls das Paper nicht existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getPaperSimple($intPaperId) {
    $s = sprintf("SELECT   id, author_id, title, last_edited, state, filename".
                 " FROM    Paper".
                 " WHERE   id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPaperSimple', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $fltAvgRating = $this->getAverageRatingOfPaper($intPaperId);
    if ($this->failed()) {
      return $this->error('getPaperSimple', $this->getLastError());
    }
    $objAuthor = $this->getPerson($data[0]['author_id']);
    if ($this->failed()) {
      return $this->error('getPaperSimple', $this->getLastError());
    }
    else if (empty($objAuthor)) {
      return $this->error('getPaperSimple', 'Fatal error: Database inconsistency!',
                          'author_id = '.$data[0]['author_id']);
    }
    $strAuthor = $objAuthor->getName(1);
    $objTopics = $this->getTopicsOfPaper($intPaperId);
    if ($this->failed()) {
      return $this->error('getPaperSimple', $this->getLastError());
    }
    $data[0]['last_edited'] = emptyDBtime($data[0]['last_edited']);
    $objPaper = (new PaperSimple($intPaperId, $data[0]['title'], $data[0]['author_id'],
                  $strAuthor, $data[0]['state'], $data[0]['last_edited'], $fltAvgRating,
                  $data[0]['filename'], $objTopics));
    return $this->success($objPaper);
  }

  /**
   * Prueft ob das Paper $intPaperId in der Konferenz $intConferenceId ist.
   *
   * @param int $intPaperId ID des Papers
   * @param int $intConferenceId ID der Konferenz
   * @return bool false, gdw. das Paper nicht in der Konferenz ist.
   * @access public
   * @author Sandro (06.02.05)
   */
  function isPaperInConference($intPaperId, $intConferenceId) {
    $s = sprintf("SELECT   id".
                 " FROM    Paper".
                 " WHERE   id = '%d'".
                 " AND     conference_id = '%d'",
                           s2db($intPaperId), s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('isPaperInConference', $this->mySql->getLastError());
    }
    return $this->success(!empty($data));
  }

  /**
   * Liefert das Paper-File mit der ID $intPaperId zurueck.
   *
   * Rueckgabe: (filename, mime_type, filesize, contents)
   *
   * @param int $intPaperId ID des Papers
   * @return (string, string, int, string) oder false, falls das Paper nicht existiert.
   * @access public
   * @author Jan (25.01.04)
   */
  function getPaperFile($intPaperId) {
    $s = sprintf("SELECT   filename, mime_type".
                 " FROM    Paper".
                 " WHERE   id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPaperFile', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    if (empty($data[0]['filename']) || empty($data[0]['mime_type'])) {
      return $this->success(false);
    }
    $s = sprintf("SELECT   filesize, file".
                 " FROM    PaperData".
                 " WHERE   paper_id = '%d'",
                           s2db($intPaperId));
    $file = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPaperFile', $this->mySql->getLastError());
    }
    else if (empty($file)) {
      return $this->error('getPaperFile', 'Expected binary file not found in database!');
    }
    return $this->success(array($data[0]['filename'], $data[0]['mime_type'],
                                $file[0]['filesize'], $file[0]['file']));
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten des Autors $intAuthorId in der
   * Konferenz $intConferenceId zurueck.
   *
   * @param int $intAuthorId ID des Autors
   * @param int $intConferenceId ID der Konferenz
   * @param int $intOrder Gibt an, wonach sortiert werden soll
   * (1=Titel, 2=Autor, 3=Status, 4=Rating, 5=Last Edit, 6=Varianz)
   * @return PaperSimple [] Ein leeres Array, falls keine Papers des Autors
   *                        $intAuthorId gefunden wurden.
   * @access public
   * @author Tom, Sandro (04.12.04, 12.12.04, 19.01.05)
   * @todo Existenz der Konferenz muss noch geprueft werden.
   */
  function getPapersOfAuthor($intAuthorId, $intConferenceId, $intOrder=false) {
    $objAuthor = $this->getPerson($intAuthorId);
    if ($this->failed()) {
       return $this->error('getPapersOfAuthor', $this->getLastError()); // dito!
    }
    else if (empty($objAuthor)) {
      return $this->success(false);
      // laut Jan und Sandro: Ungueltige Id uebergeben wie Nullpointer;
      // erhalte "leer" zurueck, aber keinen Fehler!!!
    }
    $strAuthor = $objAuthor->getName(1);
    $s = sprintf("SELECT   p.id AS id, author_id, p.title AS title, last_edited,".
                 "         p.state AS state, filename, a.last_name AS author_name".
                 " FROM    Paper p".
                 " INNER   JOIN Person a".
                 " ON      a.id = p.author_id".
                 " WHERE   p.author_id = '%d'".
                 " AND     p.conference_id = '%d'",                           
                           s2db($intAuthorId),
                           s2db($intConferenceId));
    if (!empty($intOrder)) {
      if ($intOrder == 1) {
        $s .= " ORDER BY title";
      }
      else if ($intOrder == 2) {
        $s .= " ORDER BY author_name";
      }
      else if ($intOrder == 3) {
        $s .= " ORDER BY state";
      }
      else if ($intOrder == 5) {
        $s .= " ORDER BY last_edited";
      }
    }
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPapersOfAuthor', $this->mySql->getLastError());
    }
    $objPapers = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfAuthor', $this->getLastError());
      }
      $objTopics = $this->getTopicsOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfAuthor', $this->getLastError());
      }
      $data[$i]['last_edited'] = emptyDBtime($data[$i]['last_edited']);
      $objPapers[] = (new PaperSimple($data[$i]['id'], $data[$i]['title'],
                       $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
                       $data[$i]['last_edited'], $fltAvgRating,
                       $data[$i]['filename'], $objTopics));
      // Anfragen, die Fehler erzeugen koennen (wie $this->getTopics...), nicht inline benutzen!!
    }
    if (!empty($intOrder)) {
      if ($intOrder == 4) {
        $objPapers = sortPapersByAvgRating($objPapers);
      }
      else if ($intOrder == 6) {
        $objPapers = sortPapersByAmbiguity($objPapers);
      }
    }
    return $this->success($objPapers);    
  }

  /**
   * Liefert die Anzahl der Paper des Autoren $intAuthorId in der Konferenz
   * $intConferenceId zurueck, zu denen noch kein Dokument hochgeladen wurde.   
   *
   * @param int $intAuthorId ID des Autors
   * @param int $intConferenceId ID der Konferenz   
   * @return int Die Anzahl der Paper ohne hochgeladene Dokumente
   * @access public
   * @author Sandro (06.02.05)
   * @todo Existenz der Konferenz muss noch geprueft werden.
   */
  function getNumOfPapersOfAuthorWithoutUpload($intAuthorId, $intConferenceId) {   
    $s = sprintf("SELECT   COUNT(*) AS num".
                 " FROM    Paper AS p".
                 " INNER   JOIN PaperData AS d".
                 " ON      d.paper_id = p.id".
                 " WHERE   p.author_id = '%d'".
                 " AND     p.conference_id = '%d'",                 
                           s2db($intAuthorId), s2db($intConferenceId));    
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumOfPapersOfAuthorWithoutUpload', $this->mySql->getLastError());
    }
    $numOfPapersWithUpload = $data[0]['num'];
    $s = sprintf("SELECT   COUNT(*)".
                 " FROM    Paper AS p".                 
                 " WHERE   p.author_id = '%d'".
                 " AND     p.conference_id = '%d'",                 
                           s2db($intAuthorId), s2db($intConferenceId));    
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumOfPapersOfAuthorWithoutUpload', $this->mySql->getLastError());
    }
    $numOfAllPapers = $data[0]['num'];
    return $this->success($numOfAllPapers - $numOfPapersWithUpload);
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten des Reviewers $intReviewerId
   * in der Konferenz $intConferenceId zurueck.
   *
   * @param int $intReviewerId ID des Reviewers
   * @param int $intConferenceId ID der Konferenz
   * @param int $intOrder Gibt an, wonach sortiert werden soll
   * (1=Titel, 2=Autor, 3=Status, 4=Rating, 5=Last Edit, 6=Varianz)
   * @return PaperSimple [] Ein leeres Array, falls keine Papers des Reviewers
   *                        $intReviewerId gefunden wurden.
   * @access public
   * @author Tom, Sandro (12.12.04, 19.01.05)
   * @todo Existenz der Konferenz muss noch geprueft werden.
   */
  function getPapersOfReviewer($intReviewerId, $intConferenceId, $intOrder=false) {
    $objReviewer = $this->getPerson($intReviewerId);
    if ($this->failed()) {
       return $this->error('getPapersOfReviewer', $this->getLastError());
    }
    else if (empty($objReviewer)) {
      return $this->success(false);
    }
    $s = sprintf("SELECT   p.id AS id, author_id, p.title AS title, last_edited,".
                 "         p.state AS state, filename, a.last_name AS author_name".
                 " FROM    Paper AS p".
                 " INNER   JOIN Person AS a".
                 " ON      a.id = p.author_id".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = p.id".
                 " AND     d.reviewer_id = '%d'".
                 " AND     p.conference_id = '%d'",
                           s2db($intReviewerId),
                           s2db($intConferenceId));
    if (!empty($intOrder)) {
      if ($intOrder == 1) {
        $s .= " ORDER BY title";
      }
      else if ($intOrder == 2) {
      	$s .= " ORDER BY author_name";
      }
      else if ($intOrder == 3) {
        $s .= " ORDER BY state";
      }
      else if ($intOrder == 5) {
        $s .= " ORDER BY last_edited";
      }
    }
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPapersOfReviewer', $this->mySql->getLastError());
    }
    $objPapers = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objAuthor = $this->getPerson($data[$i]['author_id']);
      if ($this->failed()) {
         return $this->error('getPapersOfReviewer', $this->getLastError());
      }
      else if (empty($objAuthor)) {
        return $this->error('getPapersOfReviewer', 'Fatal error: Database inconsistency!',
                            'author_id = '.$data[$i]['author_id']);
      }
      $strAuthor = $objAuthor->getName(1);
      $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfReviewer', $this->getLastError());
      }
      $objTopics = $this->getTopicsOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfReviewer', $this->getLastError());
      }
      $data[$i]['last_edited'] = emptyDBtime($data[$i]['last_edited']);
      $objPapers[] = (new PaperSimple($data[$i]['id'], $data[$i]['title'],
                       $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
                       $data[$i]['last_edited'], $fltAvgRating,
                       $data[$i]['filename'], $objTopics));
    }
    if (!empty($intOrder)) {
      if ($intOrder == 4) {
        $objPapers = sortPapersByAvgRating($objPapers);
      }
      else if ($intOrder == 6) {
        $objPapers = sortPapersByAmbiguity($objPapers);
      }
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten der Konferenz $intConferenceId.
   *
   * @param int $intConferenceId ID der Konferenz
   * @param int $intOrder Gibt an, wonach sortiert werden soll
   * (1=Titel, 2=Autor, 3=Status, 4=Rating, 5=Last Edit, 6=Varianz)
   * @return PaperSimple [] Ein leeres Array, falls keine Papers existieren.
   * @access public
   * @author Sandro (19.01.05)
   * @todo Existenz der Konferenz muss noch geprueft werden.
   */
  function getPapersOfConference($intConferenceId, $intOrder=false) {
    $s = sprintf("SELECT   p.id AS id, author_id, p.title AS title, last_edited,".
                 "         p.state AS state, filename, a.last_name AS author_name".
                 " FROM    Paper AS p".
                 " INNER   JOIN Person AS a".
                 " ON      a.id = p.author_id".
                 " WHERE   conference_id = '%d'",
                           s2db($intConferenceId));
    if (!empty($intOrder)) {
      if ($intOrder == 1) {
        $s .= " ORDER BY title";
      }
      else if ($intOrder == 2) {
      	$s .= " ORDER BY author_name";
      }
      else if ($intOrder == 3) {
        $s .= " ORDER BY state";
      }
      else if ($intOrder == 5) {
        $s .= " ORDER BY last_edited";
      }
    }
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPapersOfConference', $this->mySql->getLastError());
    }
    $objPapers = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objAuthor = $this->getPerson($data[$i]['author_id']);
      if ($this->failed()) {
         return $this->error('getPapersOfConference', $this->getLastError());
      }
      else if (empty($objAuthor)) {
        return $this->error('getPapersOfConference', 'Fatal error: Database inconsistency!',
                            'author_id = '.$data[$i]['author_id']);
      }
      $strAuthor = $objAuthor->getName(1);
      $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfConference', $this->getLastError());
      }
      $objTopics = $this->getTopicsOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfConference', $this->getLastError());
      }
      $data[$i]['last_edited'] = emptyDBtime($data[$i]['last_edited']);
      $objPapers[] = (new PaperSimple($data[$i]['id'], $data[$i]['title'],
                       $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
                       $data[$i]['last_edited'], $fltAvgRating,
                       $data[$i]['filename'], $objTopics));
    }
    if (!empty($intOrder)) {
      if ($intOrder == 4) {
        $objPapers = sortPapersByAvgRating($objPapers);
      }
      else if ($intOrder == 6) {
        $objPapers = sortPapersByAmbiguity($objPapers);
      }
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert PaperDetailed-Objekt mit den Daten des Papers $intPaperId.
   *
   * @param int $intPaperId ID des Papers
   * @return PaperDetailed false, falls das Paper nicht existiert.
   * @access public
   * @author Tom (12.12.04)
   */
  function getPaperDetailed($intPaperId) {
    $s = sprintf("SELECT   author_id, title, state, abstract, mime_type,".
                 "         last_edited, version, filename".
                 " FROM    Paper".
                 " WHERE   id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPaperDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $objAuthor = $this->getPerson($data[0]['author_id']);
    if ($this->failed()) {
      return $this->error('getPaperDetailed', $this->getLastError());
    }
    else if(empty($objAuthor)) {
      return $this->error('getPaperDetailed', 'Fatal error: Database inconsistency!',
                          'author_id = '.$data[0]['author_id']);
    }
    $strAuthor = $objAuthor->getName(1);
    $fltAvgRating = $this->getAverageRatingOfPaper($intPaperId);
    if ($this->failed()) {
      return $this->error('getPaperDetailed', $this->getLastError());
    }
    // Co-Autoren
    $s = sprintf("SELECT   person_id AS coauthor_id, name".
                 " FROM    IsCoAuthorOf AS i".
                 " LEFT    JOIN Person AS p".
                 " ON      p.id = i.person_id".
                 " WHERE   paper_id = '%d'".
                 " ORDER   BY person_id DESC", // ORDER BY: Co-Autoren im System im Array vorne!
                           s2db($intPaperId));
    $cadata = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPaperDetailed', $this->mySql->getLastError());
    }
    $intCoAuthorIds = array();
    $strCoAuthors = array();
    for ($i = 0; $i < count($cadata) && !empty($cadata); $i++) {
      $intCoAuthorIds[] = $cadata[$i]['coauthor_id'];
      $objCoAuthor = $this->getPerson($cadata[$i]['coauthor_id']);
      if ($this->failed()) {
        return $this->error('getPaperDetailed', $this->getLastError());
      }
      // Co-Autor nicht im System? => nimm Name aus Tabelle
      $strCoAuthors[] = empty($objCoAuthor) ?
                        $cadata[$i]['name'] :
                        $objCoAuthor->getName(1);
    }
    $objTopics = $this->getTopicsOfPaper($intPaperId);
    if ($this->failed()) {
      return $this->error('getPaperDetailed', $this->getLastError());
    }
    $data[0]['last_edited'] = emptyDBtime($data[0]['last_edited'], 'r');
    $objPaper = (new PaperDetailed($intPaperId, $data[0]['title'], $data[0]['author_id'],
                  $strAuthor, $data[0]['state'], $data[0]['last_edited'], $fltAvgRating,
                  $intCoAuthorIds, $strCoAuthors, $data[0]['abstract'], $data[0]['mime_type'],
                  $data[0]['version'], $data[0]['filename'], $objTopics));
    return $this->success($objPaper);
  }

  /**
   * Liefert ein Array von Topic-Objekten der Themen zurueck, die mit dem
   * Paper $intPaperId assoziiert sind.
   *
   * @param int $intPaperId ID des Papers
   * @return Topic [] Gibt ein leeres Array zurueck, wenn das Paper mit keinem
   *                  Thema assoziiert ist.
   * @access public
   * @author Sandro, Tom (17.12.04)
   */
  function getTopicsOfPaper($intPaperId) {
    $s = sprintf("SELECT   t.id AS id, t.name AS name".
                 " FROM    Topic AS t".
                 " INNER   JOIN IsAboutTopic AS a".
                 " ON      a.topic_id = t.id".
                 " WHERE   a.paper_id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getTopicsOfPaper', $this->mySql->getLastError());
    }
    $objTopics = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objTopics[] = (new Topic($data[$i]['id'], $data[$i]['name']));
    }
    return $this->success($objTopics);
  }

  /**
   * Liefert den Durchschnitt der Gesamtbewertungen des Papers $intPaperId.
   *
   * @param int $intPaperId ID des Papers
   * @return float false, falls keine Bewertungen des Papers gefunden wurden.
   * @access private
   * @author Sandro, Tom (06.12.04, 12.12.04)
   */
  function getAverageRatingOfPaper($intPaperId) {
    $s = sprintf("SELECT   SUM((r.grade/c.max_value)*(c.quality_rating/100)) AS total_rating".
                 " FROM    ReviewReport AS rr".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = rr.paper_id".
                 " AND     d.reviewer_id = rr.reviewer_id".
                 " INNER   JOIN Rating AS r".
                 " ON      r.review_id = rr.id".
                 " INNER   JOIN Criterion AS c".
                 " ON      c.id = r.criterion_id".
                 " WHERE   rr.paper_id = '%d'".
                 " GROUP   BY rr.id",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getAverageRatingOfPaper', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $sum = 0;
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $sum += $data[$i]['total_rating'];
    }
    return $this->success($sum / count($data));

    /*$s = sprintf("SELECT   c.id AS id, c.max_value AS max_value, c.quality_rating/100 AS weight,".
                 "         SUM(r.grade) AS sum_grade, COUNT(*) AS num".
                 " FROM    ReviewReport AS rr".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = rr.paper_id".
                 " AND     d.reviewer_id = rr.reviewer_id".
                 " INNER   JOIN Rating AS r".
                 " ON      r.review_id = rr.id".
                 " INNER   JOIN Criterion AS c".
                 " ON      c.id = r.criterion_id".
                 " WHERE   rr.paper_id = '%d'".
                 " GROUP   BY c.id, c.max_value, c.quality_rating",
                           s2db($intPaperId)); 
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getAverageRatingOfPaper', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    echo '<pre>'.str_replace(array("\n" , " "), array('<br>', '&nbsp;'), print_r($data, true)).'</pre>';
    for ($i = 0; $i < count($data); $i++) {
      
    }*/
  }

  /**
   * Liefert die Varianz des Papers mit ID $intPaperId.
   *
   * @param int $intPaperId Paper-ID.
   * @return Die Varianz, sofern mindestens ein Review existiert. False, wenn
   *        dies nicht der Fall ist.
   *
   * @access public
   * @author Falk, Tom (20.01.05)
   */
  function getVarianceOfPaper($intPaperId) {
    $objReviews = $this->getReviewsOfPaper($intPaperId, false);
    if ($this->failed()) {
      return $this->error('getVarianceOfPaper', $this->getLastError());
    }
    if (!empty($objReviews)) {
      $fltAvgRating = $objReviews[0]->fltAverageRating;
      $fltVariance = 0.0;
      foreach ($objReviews as $objReview){
        $fltVariance = $fltVariance + pow(($objReview->fltReviewRating - $fltAvgRating), 2);
      }
      $fltVariance = sqrt($fltVariance / count($objReviews)); // jetzt Standardabweichung!!
      return $this->success($fltVariance*2); // Faktor 2 fuer Skalierung auf 0..1
    }
    return $this->success(false);
  }

  /**
   * Liefert die Gesamtbewertung eines Reviews (die Durchschnittsnote aller
   * Kriterien unter Beruecksichtigung der Gewichtungen).
   *
   * @param int $intReviewId ID des Reviews
   * @return float false, falls keine Ratings zu den Kriterien gemacht wurden.
   * @access private
   * @author Sandro, Tom (06.12.04, 12.12.04)
   */
  function getReviewRating($intReviewId) {
    $s = sprintf("SELECT   SUM((r.grade/c.max_value)*(c.quality_rating/100)) AS total_rating".
                 " FROM    Rating AS r".
                 " INNER   JOIN Criterion AS c".
                 " ON      c.id = r.criterion_id".
                 " AND     r.review_id = '%d'",
                           s2db($intReviewId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewRating', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    return $this->success($data[0]['total_rating']);
  }

  /**
   * Liefert ein Array von Review-Objekten des Reviewers $intReviewerId in
   * der Konferenz $intConferenceId zurueck.
   *
   * @param int $intReviewerId ID des Reviewers
   * @param int $intConferenceId ID der Konferenz
   * @return Review Ein leeres Array, falls kein Review des Reviewers existiert.
   * @access public
   * @author Sandro (14.12.04)
   * @todo Existenz der Konferenz muss noch geprueft werden.
   */
  function getReviewsOfReviewer($intReviewerId, $intConferenceId) {
    $s = sprintf("SELECT   r.id".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Paper AS p".
                 " ON      p.id = r.paper_id".
                 " AND     p.conference_id = '%d'".
                 " AND     r.reviewer_id = '%d'".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id",
                           s2db($intConferenceId),
                           s2db($intReviewerId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewsOfReviewer', $this->mySql->getLastError());
    }
    $objReviews = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objReview = $this->getReview($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getReviewsOfReviewer', $this->getLastError());
      }
      else if (empty($objReview)) {
        return $this->error('getReviewsOfReviewer', 'Fatal error: Database inconsistency!',
                            'id = '.$data[$i]['id']);
      }
      $objReviews[] = $objReview;
    }
    return $this->success($objReviews);
  }

  /**
   * Liefert die Anzahl der bisherigen Reviews des Papers $intPaperId zurueck.
   *
   * @param int $intPaperId ID des Papers
   * @return int Anzahl der Reviews.
   * @access public
   * @author Tom (26.01.05)
   */
  function getNumberOfReviewsOfPaper($intPaperId) {
    $s = sprintf("SELECT   COUNT(*) AS num".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id".
                 " WHERE   r.paper_id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumberOfReviewsOfPaper', $this->mySql->getLastError());
    }
    if (empty($data)) { // sollte nicht vorkommen
      return $this->error('getNumberOfReviewsOfPaper', 'Empty result set.');
    }
    return $this->success($data[0]['num']);
  }

  /**
   * Liefert die Anzahl der Paper in der Konferenz $intConferenceId zurueck.
   * 
   * @param int $intConferenceId ID der Konferenz.
   * @return int Anzahl der Paper.
   * @access public
   * @author Sandro (01.02.05)
   */
  function getNumberOfPapers($intConferenceId) {
    $s = sprintf("SELECT   COUNT(*) AS num".
                 " FROM    Paper AS p".
                 " WHERE   p.conference_id = '%d'",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumberOfPapers', $this->mySql->getLastError());
    }
    return $this->success(!empty($data) ? $data[0]['num'] : 0);
  }

  /**
   * Liefert die Anzahl der kritischen Paper in der Konferenz $intConferenceId,
   * optional: die vom Reviewer $intReviewerId bewertet werden.
   * 
   * @param int $intConferenceId ID der Konferenz.
   * @param int $intReviewerId Fuer false werden alle Paper der Konferenz geprueft.
   * @return int Anzahl der kritischen Paper.
   * @access public
   * @author Sandro (01.02.05)
   */
  function getNumberOfCriticalPapers($intConferenceId, $intReviewerId=false) {
    if (empty($intReviewerId)) {
      $objPapers = $this->getPapersOfConference($intConferenceId);
    }
    else {
      $objPapers = $this->getPapersOfReviewer($intReviewerId, $intConferenceId);
    }
    if ($this->failed()) {
      return $this->error('getNumberOfCriticalPapers', $this->getLastError());
    }
    // Kritische Varianz bestimmen
    $s = sprintf("SELECT   critical_variance".
                 " FROM    ConferenceConfig".
                 " WHERE   id = '%d'",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumberOfCriticalPapers', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->error('getNumberOfCriticalPapers', 'Conference does not exist in database.');
    }
    $fltCriticalVariance = $data[0]['critical_variance'];    
    $num = 0;
    foreach ($objPapers as $objPaper) {      
      $fltVariance = $this->getVarianceOfPaper($objPaper->intId);
      if ($this->failed()) {
        return $this->error('getNumberOfCriticalPapers', $this->getLastError());
      }
      else if ($fltVariance >= $fltCriticalVariance) {
      	$num++;
      }
    }
    return $this->success($num);
  }

  /**
   * Liefert die ID's der kritischen Paper in der Konferenz $intConferenceId.
   * 
   * @param int $intConferenceId ID der Konferenz.
   * @return int Array von ID's der kritischen Paper.
   * @access public
   * @author Tom (02.02.05)
   */
  function getCriticalPaperIds($intConferenceId) {
    $intPapers = array();
    $objPapers = $this->getPapersOfConference($intConferenceId);
    if ($this->failed()) {
      return $this->error('getNumberOfCriticalPapers', $this->getLastError());
    }
    elseif (empty($objPapers)) {
      return $this->success($intPapers);
    }
    // Kritische Varianz bestimmen
    $s = sprintf("SELECT   critical_variance".
                 " FROM    ConferenceConfig".
                 " WHERE   id = '%d'",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getCriticalPaperIds', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->error('getCriticalPaperIds', 'Conference does not exist in database.');
    }
    $fltCriticalVariance = $data[0]['critical_variance'];    
    foreach ($objPapers as $objPaper) {      
      $fltVariance = $this->getVarianceOfPaper($objPaper->intId);
      if ($this->failed()) {
        return $this->error('getCriticalPaperIds', $this->getLastError());
      }
      else if ($fltVariance >= $fltCriticalVariance) {
      	$intPapers[] = $objPaper->intId;
      }
    }
    return $this->success($intPapers);
  }

  /**
   * Liefert die Anzahl der bereits zugeteilten Paper in der
   * Konferenz $intConferenceId zurueck.
   * 
   * @param int $intConferenceId ID der Konferenz.
   * @return int Anzahl der verteilten Paper.
   * @access public
   * @author Sandro (01.02.05)
   */
  function getNumberOfDistributedPapers($intConferenceId) {
    $s = sprintf("SELECT   COUNT(*) AS num".
                 " FROM    Paper AS p".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = p.id".
                 " WHERE   p.conference_id = '%d'".
                 " GROUP   BY p.id",
                           s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumberOfDistributedPapers', $this->mySql->getLastError());
    }
    return $this->success(!empty($data) ? count($data) : 0);
  }

  /**
   * Liefert die Anzahl der zugeteilten Reviewer des Papers $intPaperId in der
   * Konferenz $intConferenceId zurueck.
   *
   * @param int $intReviewerId ID des Reviewers
   * @param int $intConferenceId ID der Konferenz
   * @return int Anzahl der dem Reviewer zugeteilten Paper.
   * @access public
   * @author Tom (01.02.05)
   */
  function getNumberOfPapersOfReviewer($intReviewerId, $intConferenceId) {
    $s = sprintf("SELECT   COUNT(*) AS num".
                 " FROM    Distribution AS d".
                 " INNER   JOIN Paper AS p".
                 " ON      p.id = d.paper_id".
                 " AND     d.reviewer_id = '%d'".
                 " AND     p.conference_id = '%d'",
                           s2db($intReviewerId), s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumberOfPapersOfReviewer', $this->mySql->getLastError());
    }
    if (empty($data)) { // sollte nicht vorkommen
      return $this->error('getNumberOfPapersOfReviewer', 'Empty result set.');
    }
    return $this->success($data[0]['num']);
  }

  /**
   * Liefert die Anzahl der Paper mit dem Status $intStatus in der Konferenz
   * $intConferenceId zurueck.
   * 
   * @param int $intStatus Status des Papers (PAPER_ACCEPTED, PAPER_REJECTED, PAPER_CRITICAL).
   * @param int $intConferenceId ID der Konferenz.
   * @return int Anzahl der Paper des Status.
   * @access public
   * @author Sandro (01.02.05)
   */
  function getNumberOfPapersWithStatus($intStatus, $intConferenceId) {
    $s = sprintf("SELECT   COUNT(*) AS num".
                 " FROM    Paper AS p".
                 " WHERE   p.state = '%d'".
                 " AND     p.conference_id = '%d'",
                           s2db($intStatus), s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNumberOfPapersWithStatus', $this->mySql->getLastError());
    }
    return $this->success(!empty($data) ? $data[0]['num'] : 0);
  }

  /**
   * Prueft, ob das Paper $intPaperId vom Reviewer $intReviewerId bereits
   * bewertet worden ist.
   *
   * @param int $intPaperId ID des Papers
   * @param int $intReviewerId ID des Reviewers
   * @return bool true gdw. das Paper bereits bewertet worden ist
   * @access public
   * @author Sandro (28.01.05)
   */
  function hasPaperBeenReviewed($intPaperId, $intReviewerId) {
    $s = sprintf("SELECT   r.reviewer_id".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id".
                 " WHERE   d.paper_id = '%d'".
                 " AND     r.reviewer_id = '%d'",
                           s2db($intPaperId), s2db($intReviewerId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('hasPaperBeenReviewed', $this->mySql->getLastError());
    }
    return $this->success(!empty($data));
  }

  /**
   * Prueft, ob das Paper $intPaperId dem Reviewer $intReviewerId zugeteilt ist.
   *
   * @param int $intPaperId ID des Papers
   * @param int $intReviewerId ID des Reviewers
   * @return bool true gdw. das Paper dem Reviewer zugeteilt ist
   * @access public
   * @author Sandro (28.01.05)
   */
  function isPaperDistributedTo($intPaperId, $intReviewerId) {
    $s = sprintf("SELECT   reviewer_id".
                 " FROM    Distribution".
                 " WHERE   paper_id = '%d'".
                 " AND     reviewer_id = '%d'",
                           s2db($intPaperId), s2db($intReviewerId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('isPaperDistributedTo', $this->mySql->getLastError());
    }
    return $this->success(!empty($data));
  }

  /**
   * Prueft, ob das Paper $intPaperId kritisch ist.
   *
   * @param int $intPaperId ID des Papers   
   * @return bool true gdw. das Paper kritisch ist
   * @access public
   * @author Sandro (04.02.05)
   */
  function isPaperCritical($intPaperId) { 
    $s = sprintf("SELECT   state".
                 " FROM    Paper".
                 " WHERE   id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('isPaperCritical', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->error('isPaperCritical', 'Paper '.$intPaperId.'does not exist in database.');
    }
    return $this->success($data[0]['state'] == PAPER_CRITICAL);
  }

  /**
   * Liefert ein Array von Person-Objekten zurueck, die Reviewer des Papers $intPaperId sind.
   *
   * @param int $intPaperId ID des Papers
   * @return Person [] Ein leeres Array, falls das Paper keine Reviewer besitzt.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewersOfPaper($intPaperId) {
    $s = sprintf("SELECT   reviewer_id".
                 " FROM    Distribution".
                 " WHERE   paper_id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewersOfPaper', $this->mySql->getLastError());
    }
    $objReviewers = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objReviewer = $this->getPerson($data[$i]['reviewer_id']);
      if ($this->mySql->failed()) {
        return $this->error('getReviewersOfPaper', $this->mySql->getLastError());
      }
      else if(empty($objReviewer)) {
        return $this->error('getReviewersOfPaper', 'Fatal error: Database inconsistency!',
                            'reviewer_id = '.$data[$i]['reviewer_id']);
      }
      $objReviewers[] = $objReviewer;
    }
    return $this->success($objReviewers);
  }

  /**
   * Liefert ein Array von Review-Objekten des Papers $intPaperId zurueck.
   *
   * @param int $intPaperId ID des Papers
   * @return Review [] Ein leeres Array, falls kein Review des Papers existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewsOfPaper($intPaperId) {
    $s = sprintf("SELECT   r.id".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id".
                 " WHERE   r.paper_id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewsOfPaper', $this->mySql->getLastError());
    }
    $objReviews = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objReview = $this->getReview($data[$i]['id']);
      if ($this->mySql->failed()) {
        return $this->error('getReviewsOfPaper', $this->mySql->getLastError());
      }
      else if (empty($objReview)) {
        return $this->error('getReviewsOfPaper', 'Fatal error: Database inconsistency!',
                            'id = '.$data[$i]['id']);
      }
      $objReviews[] = $objReview;
    }
    return $this->success($objReviews);
  }

  /**
   * Liefert ein die Id eines Reviews des Reviewers $intReviewerId zum
   * Papers $intPaperId zurueck.
   *
   * @param int $intReviewerId ID des Reviewers
   * @param int $intPaperId ID des Papers
   * @return int ID des Reviews bzw. false, falls der Reviewer das Paper nicht bewertet.
   * @access public
   * @author Sandro (28.01.05)
   */
  function getReviewIdOfReviewerAndPaper($intReviewerId, $intPaperId) {
    $s = sprintf("SELECT   r.id".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id".
                 " WHERE   r.paper_id = '%d'".
                 " AND     r.reviewer_id = '%d'",
                           s2db($intPaperId), s2db($intReviewerId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewerReviewOfPaper', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    return $this->success($data[0]['id']);
  }

  /**
   * Liefert ein Review-Objekt mit den Daten des Reviews $intReviewId zurueck.
   *
   * @param int $intReviewId ID des Reviews
   * @return Review false, falls das Review nicht existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReview($intReviewId) {
    $s = sprintf("SELECT   r.id, r.paper_id, r.reviewer_id".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id".
                 " WHERE   r.id = '%d'",
                           s2db($intReviewId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReview', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $objReviewer = $this->getPerson($data[0]['reviewer_id']);
    if ($this->failed()) {
      return $this->error('getReview', $this->getLastError());
    }
    else if (empty($objReviewer)) {
        return $this->error('getReview', 'Fatal error: Database inconsistency!',
                            'reviewer_id = '.$data[$i]['reviewer_id']);
    }
    $objPaper = $this->getPaperSimple($data[0]['paper_id']);
    if ($this->failed()) {
      return $this->error('getReview', $this->getLastError());
    }
    else if (empty($objPaper)) {
      return $this->error('getReview', 'Fatal error: Database inconsistency!',
                          'paper_id = '.$data[0]['paper_id']);
    }
    $objAuthor = $this->getPerson($objPaper->intAuthorId);
    if ($this->failed()) {
      return $this->error('getReview', $this->getLastError());
    }
    else if (empty($objAuthor)) {
      return $this->error('getReview', 'Fatal error: Database inconsistency!',
                          "intAuthorId = $objPaper->intAuthorId");
    }
    $fltAvgRating = $this->getAverageRatingOfPaper($data[0]['paper_id']);
    if ($this->failed()) {
      return $this->error('getReview', $this->getLastError());
    }
    $objReview = (new Review($data[0]['id'], $data[0]['paper_id'], $objPaper->strTitle,
                   $objAuthor->intId, $objAuthor->getName(1), $this->getReviewRating($intReviewId),
                   $fltAvgRating, $objReviewer->intId, $objReviewer->getName(1)));
    return $this->success($objReview);
  }

  /**
   * Liefert ein ReviewDetailed-Objekt mit den Daten des Reviews $intReviewId zurueck.
   *
   * @param int $intReviewId ID des Reviews
   * @return ReviewDetailed false, falls das Review nicht existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewDetailed($intReviewId) {
    $s = sprintf("SELECT   r.id, r.paper_id, r.reviewer_id, r.summary, r.remarks, r.confidential".
                 " FROM    ReviewReport AS r".
                 " INNER   JOIN Distribution AS d".
                 " ON      d.paper_id = r.paper_id".
                 " AND     d.reviewer_id = r.reviewer_id".
                 " WHERE   r.id = '%d'",
                           s2db($intReviewId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $objReviewer = $this->getPerson($data[0]['reviewer_id']);
    if ($this->failed()) {
      return $this->error('getReviewDetailed', $this->getLastError());
    }
    else if (empty($objReviewer)) {
        return $this->error('getReviewDetailed', 'Fatal error: Database inconsistency!',
                            'reviewer_id = '.$data[$i]['reviewer_id']);
    }
    $objPaper = $this->getPaperSimple($data[0]['paper_id']);
    if ($this->failed()) {
      return $this->error('getReviewDetailed', $this->getLastError());
    }
    else if (empty($objPaper)) {
      return $this->error('getReviewDetailed', 'Fatal error: Database inconsistency!',
                          'paper_id = '.$data[0]['paper_id']);
    }
    $objAuthor = $this->getPerson($objPaper->intAuthorId);
    if ($this->failed()) {
      return $this->error('getReviewDetailed', $this->getLastError());
    }
    else if (empty($objAuthor)) {
      return $this->error('getReviewDetailed', 'Fatal error: Database inconsistency!',
                          'intAuthorId = '.$objPaper->intAuthorId);
    }
    $s = sprintf("SELECT  r.grade, r.comment, c.id, c.name, c.description, c.max_value,".
                 "         c.quality_rating".
                 " FROM    Rating r".
                 " INNER   JOIN Criterion c".
                 " ON      c.id  = r.criterion_id".
                 " WHERE   review_id = '%d'",
                           s2db($data[0]['id']));
    $rating_data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewDetailed', $this->mySql->getLastError());
    }
    $intRatings = array();
    $strComments = array();
    $objCriterions = array();
    for ($i = 0; $i < count($rating_data) && !empty($rating_data); $i++) {
      $intRatings[] = $rating_data[$i]['grade'];
      $strComments[] = $rating_data[$i]['comment'];
      $objCriterions[] = (new Criterion($rating_data[$i]['id'], $rating_data[$i]['name'],
                            $rating_data[$i]['description'], $rating_data[$i]['max_value'],
                            $rating_data[$i]['quality_rating'] / 100.0));
    }
    $fltReviewRating = $this->getReviewRating($intReviewId);
    if ($this->failed()) {
      return $this->error('getReviewDetailed', $this->getLastError());
    }
    $fltAvgRating = $this->getAverageRatingOfPaper($data[0]['paper_id']);
    if ($this->failed()) {
      return $this->error('getReviewDetailed', $this->getLastError());
    }
    $objReview = (new ReviewDetailed($data[0]['id'], $data[0]['paper_id'],
                   $objPaper->strTitle, $objAuthor->intId, $objAuthor->getName(1),
                   $fltReviewRating, $fltAvgRating, $objReviewer->intId,
                   $objReviewer->getName(1), $data[0]['summary'], $data[0]['remarks'],
                   $data[0]['confidential'], $intRatings, $strComments, $objCriterions));
    return $this->success($objReview);
  }

  /**
   * Prueft, ob die Person $objPerson Zugang zum Forum $objForum hat.
   *
   * @param Person $objPerson Die zu pruefende Person.
   * @param Forum $objForum Das zu pruefende Forum.
   * @return bool Gibt true zurueck gdw. die Person Zugriff auf das Forum hat
   * @access public
   * @author Sandro (27.02.05)
   * @todo Zu klaeren: Duerfen Reviewer zu allen Foren Zugang haben?
   */
  function checkAccessToForum($objPerson, $objForum) {
    $blnAccess = false;
    if ($objForum->intForumType == FORUM_PUBLIC) {
      if ($objPerson->hasAnyRole()) {
        $blnAccess = true;
      }
    }
    else if ($objForum->intForumType == FORUM_PRIVATE) {
      if ($objPerson->hasRole(CHAIR) || $objPerson->hasRole(REVIEWER)) {
        $blnAccess = true;
      }
    }
    else if ($objForum->intForumType == FORUM_PAPER) {
      $objPaper = $this->getPaperSimple($objForum->intPaperId);
      if ($this->failed()) {
        return $this->error('checkAccessToForum', $this->getLastError());
      }
      else if (empty($objPaper)) {
        return $this->error('checkAccessToForum', 'Paper does not exist in database.');
      }
      if (($objPerson->hasRole(CHAIR) || $objPerson->hasRole(REVIEWER)) &&
          ($objPerson->intId != $objPaper->intAuthorId)) {
        $blnAccess = true;
      }
    }
    return $this->success($blnAccess);
  }

  /**
   * Liefert ein Array von Message-Objekten zurueck, die (direkte) Antworten auf die
   * Message $intMessageId sind.
   *
   * @param int $intMessageId ID der Message
   * @return Message [] Ein leeres Array, wenn die Message keine Antworten besitzt.
   * @access private
   * @author Sandro (14.12.04)
   */
  function getNextMessages($intMessageId) {
    $s = sprintf("SELECT   id, sender_id, send_time, subject, text".
                 " FROM    Message".
                 " WHERE   reply_to = '%d'",
                           s2db($intMessageId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNextMessages', $this->mySql->getLastError());
    }
    $messages = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $data[$i]['send_time'] = emptyDBtime($data[$i]['send_time'], 'r');
      $messages[] = (new Message($data[$i]['id'], $data[$i]['sender_id'],
                       $data[$i]['send_time'], $data[$i]['subject'],
                       $data[$i]['text'], $this->getNextMessages($data[$i]['id'])));
    }
    return $this->success($messages);
  }

  /**
   * Liefert ein Array von Message-Objekten zurueck, welche die Wurzelknoten
   * von Threads des Forums $intForumId sind (im folgenden synonym mit Thread verwendet).
   *
   * @param int $intForumId ID des Forums
   * @return Message[] Ein leeres Array, wenn das Forum keine Threads besitzt.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getThreadsOfForum($intForumId) {
    $s = sprintf("SELECT   id, sender_id, send_time, subject, text".
                 " FROM    Message".
                 " WHERE   forum_id = '%d'".
                 " AND     reply_to IS NULL",
                           s2db($intForumId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getThreadsOfForum', $this->mySql->getLastError());
    }
    $objThreads = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $data[$i]['send_time'] = emptyDBtime($data[$i]['send_time'], 'r');
      $objThreads[] = (new Message($data[$i]['id'], $data[$i]['sender_id'],
                         $data[$i]['send_time'], $data[$i]['subject'],
                         $data[$i]['text'], $this->getNextMessages($data[$i]['id'])));
      if ($this->mySql->failed()) {
        return $this->error('getThreadsOfForum', $this->mySql->getLastError());
      }
    }
    return $this->success($objThreads);
  }

  /**
   * Liefert ein Array von Forum-Objekten der Konferenz $intConferenceId zurueck
   * bzw. die globalen Konferenzforen, wenn keine Konferenz-ID angegeben wurde.
   *
   * @param int $intConferenceId Die ID der Konferenz, deren Foren ermittelt werden sollen.
   *                             Optional: Bei Nichtangabe werden die globalen Foren ermittelt.
   * @return Forum [] Ein leeres Array, falls kein Forum existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getAllForums($intConferenceId=false) {
    if (empty($intConferenceId)) {
      $s = sprintf("SELECT   id, title, forum_type, paper_id".
                   " FROM    Forum".
                   " WHERE   forum_type = '%d'",
                             s2db(FORUM_GLOBAL));
    }
    else {
      $s = sprintf("SELECT   id, title, forum_type, paper_id".
                   " FROM    Forum".
                   " WHERE   conference_id = '%d'",
                             s2db($intConferenceId));
    }
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getAllForums', $this->mySql->getLastError());
    }
    $objForums = array();
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objForums[] = (new Forum($data[$i]['id'], $data[$i]['title'],
                                $intConferenceId, $data[$i]['forum_type'],
                               ($data[$i]['forum_type'] == FORUM_PAPER) ?
                                $data[$i]['paper_id'] : false));
    }
    return $this->success($objForums);
  }

  /**
   * Liefert das Forum-Objekt des Forums zurueck, das mit Paper $intPaperId assoziiert ist.
   *
   * @return Forum false, falls kein Forum zu dem Paper existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getForumOfPaper($intPaperId) {
    $s = sprintf("SELECT   id, title, conference_id".
                 " FROM    Forum".
                 " WHERE   paper_id = '%d'",
                           s2db($intPaperId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getForumOfPaper', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $forum = (new Forum($data[0]['id'], $data[0]['title'], $data[0]['conference_id'],
                        FORUM_PAPER, $intPaperId));
    return $this->success($forum);
  }

  /**
   * Liefert ein Array von Forum-Objekten aller Foren zurueck, welche die Person
   * $intPersonId in der Konferenz $intConferenceId einsehen darf.
   *
   * @param int $intPersonId Die ID der zu pruefenden Person.
   * @param int $intConferenceId Die ID der Konferenz, aus der die Foren ermittelt werden sollen.
   * @return Forum [] Ein leeres Array, falls keine solchen Foren existieren.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getForumsOfPerson($intPersonId, $intConferenceId=false) {
    $objForums = array();
    $objPerson = $this->getPerson($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('getForumsOfPerson', $this->getLastError());
    }
    else if (empty($objPerson)) {
      return $this->success($objForums);
    }
    $objAllForums = $this->getAllForums($intConferenceId);
    if ($this->failed()) {
      return $this->error('getForumsOfPerson', $this->getLastError());
    }
    else if (empty($objAllForums)) {
      return $this->success($objForums);
    }
    for ($i = 0; $i < count($objAllForums) && !empty($objAllForums); $i++) {
      $blnAccess = $this->checkAccessToForum($objPerson, $objAllForums[$i]);
      if ($this->failed()) {
        return $this->error('getForumsOfPerson', $this->getLastError());
      }
      if ($blnAccess) {
        $objForums[] = $objAllForums[$i];
      }
    }
    return $this->success($objForums);
  }

  /**
   * Liefert ein einfaches Forum-Objekt mit den Daten des Forums $intForumId zurueck.
   *
   * @return Forum false, falls das Forum nicht existiert.
   * @access public
   * @author Sandro (28.01.05)
   */
  function getForum($intForumId) {
    $s = sprintf("SELECT   id, title, forum_type, paper_id, conference_id".
                 " FROM    Forum".
                 " WHERE   id = '%d'",
                           s2db($intForumId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getForumDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $forum = (new Forum($data[0]['id'], $data[0]['title'],
                        $data[0]['conference_id'],
                        $data[0]['forum_type'],
                       ($data[0]['forum_type'] == FORUM_PAPER ?
                        $data[0]['paper_id'] : false)));
    return $this->success($forum);
  }

  /**
   * Liefert ein ForumDetailed-Objekt mit den Daten des Forums $intForumId zurueck.
   * Das ForumDetailed-Objekt enthaelt den kompletten Message-Baum des Forums.
   *
   * @return ForumDetailed false, falls das Forum nicht existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getForumDetailed($intForumId) {
    $s = sprintf("SELECT   id, title, forum_type, paper_id, conference_id".
                 " FROM    Forum".
                 " WHERE   id = '%d'",
                           s2db($intForumId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getForumDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $forum = (new ForumDetailed($data[0]['id'], $data[0]['title'],
                                $data[0]['conference_id'],
                                $data[0]['forum_type'],
                               ($data[0]['forum_type'] == FORUM_PAPER ?
                                $data[0]['paper_id'] : false),
                                $this->getThreadsOfForum($intForumId)));
    return $this->success($forum);
  }

  /**
   * Liefert ein Array der bevorzugten Topics der Person mit ID $intPersonId
   * bei der Konferenz $intConferenceId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId Konferenz-ID
   * @return Topic [] Ein leeres Array, falls keine bevorzugten Topics existieren.
   * @access private
   * @author Tom (18.01.05)
   */
  function getPreferredTopics($intPersonId, $intConferenceId) {
    $objTopics = array();
    $s = sprintf("SELECT   pt.topic_id AS topic_id, t.name AS name".
                 " FROM    PrefersTopic pt".
                 " INNER   JOIN Topic t".
                 " ON      t.id = pt.topic_id".
                 " AND     t.conference_id = '%d'".
                 " WHERE   pt.person_id = '%d'",
                           s2db($intConferenceId),
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPreferredTopics', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objTopics[] = new Topic($data[$i]['topic_id'], $data[$i]['name']);
    }
    return $this->success($objTopics);
  }

  /**
   * Liefert ein Array der bevorzugten Paper der Person mit ID $intPersonId
   * bei der Konferenz $intConferenceId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId Konferenz-ID
   * @return PaperSimple [] Ein leeres Array, falls keine bevorzugten Paper existieren.
   * @access private
   * @author Tom (18.01.05)
   */
  function getPreferredPapers($intPersonId, $intConferenceId) {
    $objPapers = array();
    $s = sprintf("SELECT   pp.paper_id AS paper_id".
                 " FROM    PrefersPaper pp".
                 " INNER   JOIN Paper p".
                 " ON      p.id = pp.paper_id".
                 " AND     p.conference_id = '%d'".
                 " WHERE   pp.person_id = '%d'",
                           s2db($intConferenceId),
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPreferredPapers', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objPapers[] = $this->getPaperSimple($data[$i]['paper_id']);
      if ($this->failed()) {
        return $this->error('getPreferredPapers', $this->getLastError());
      }
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert ein Array der durch die Person $intPersonId zum Review abgelehnten Paper
   * bei der Konferenz $intConferenceId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId Konferenz-ID
   * @return PaperSimple [] Ein leeres Array, falls keine abgelehnten Paper existieren.
   * @access private
   * @author Tom (18.01.05)
   */
  function getDeniedPapers($intPersonId, $intConferenceId) {
    $objPapers = array();
    $s = sprintf("SELECT   dp.paper_id AS paper_id".
                 " FROM    DeniesPaper dp".
                 " INNER   JOIN Paper p".
                 " ON      p.id = dp.paper_id".
                 " AND     p.conference_id = '%d'".
                 " WHERE   dp.person_id = '%d'",
                           s2db($intConferenceId),
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDeniedPapers', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objPapers[] = $this->getPaperSimple($data[$i]['paper_id']);
      if ($this->failed()) {
        return $this->error('getDeniedPapers', $this->getLastError());
      }
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert ein Array der fuer die Person mit ID $intPersonId ausgeschlossenen Paper
   * bei der Konferenz $intConferenceId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId Konferenz-ID
   * @return PaperSimple [] Ein leeres Array, falls keine ausgeschlossenen Paper existieren.
   * @access private
   * @author Tom (18.01.05)
   */
  function getExcludedPapers($intPersonId, $intConferenceId) {
    $objPapers = array();
    $s = sprintf("SELECT   ep.paper_id AS paper_id".
                 " FROM    ExcludesPaper ep".
                 " INNER   JOIN Paper p".
                 " ON      p.id = ep.paper_id".
                 " AND     p.conference_id = '%d'".
                 " WHERE   ep.person_id = '%d'",
                           s2db($intConferenceId),
                           s2db($intPersonId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getExcludedPapers', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data) && !empty($data); $i++) {
      $objPapers[] = $this->getPaperSimple($data[$i]['paper_id']);
      if ($this->failed()) {
        return $this->error('getExcludedPapers', $this->getLastError());
      }
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert ein ReviewerAttitude-Objekt zum Reviewer $intPersonId in der Konferenz
   * $intConferenceId zurueck, also eine Abbildung von Topic- und Paper-IDs auf
   * Praeferenzen (i.e.: none, prefer, exclude, deny).
   *
   * @param int $intPersonId ID des Reviewers
   * @param int $intConferenceId Konferenz-ID
   * @return ReviewerAttitude
   * @access private
   * @author Sandro (22.01.05)
   */
  function getReviewerAttitude($intPersonId, $intConferenceId) {
    $objReviewerAttitude = new ReviewerAttitude($intPersonId, $intConferenceId);
    $objPapers = $this->getPreferredPapers($intPersonId, $intConferenceId);
    if ($this->mySql->failed()) {
      return $this->error('getReviewerAttitude', $this->mySql->getLastError());
    }
    foreach ($objPapers as $objPaper) {
      $objReviewerAttitude->setPaperAttitude($objPaper->intId, ATTITUDE_PREFER);
    }
    $objTopics = $this->getPreferredTopics($intPersonId, $intConferenceId);
    if ($this->mySql->failed()) {
      return $this->error('getReviewerAttitude', $this->mySql->getLastError());
    }
    foreach ($objTopics as $objTopic) {
      $objReviewerAttitude->setTopicAttitude($objTopic->intId, ATTITUDE_PREFER);
    }
    $objPapers = $this->getDeniedPapers($intPersonId, $intConferenceId);
    if ($this->mySql->failed()) {
      return $this->error('getReviewerAttitude', $this->mySql->getLastError());
    }
    foreach ($objPapers as $objPaper) {
      $objReviewerAttitude->setPaperAttitude($objPaper->intId, ATTITUDE_DENY);
    }
    $objPapers = $this->getExcludedPapers($intPersonId, $intConferenceId);
    if ($this->mySql->failed()) {
      return $this->error('getReviewerAttitude', $this->mySql->getLastError());
    }
    foreach ($objPapers as $objPaper) {
      $objReviewerAttitude->setPaperAttitude($objPaper->intId, ATTITUDE_EXCLUDE);
    }
    return $this->success($objReviewerAttitude);
  }

  // ---------------------------------------------------------------------------
  // Definition der Update-Funktionen
  // ---------------------------------------------------------------------------

/* =============================================================================
Brauchen wir ueberhaupt Updatemethoden fuer "simple" Objekte, oder i.d.R. immer
nur fuer detaillierte?
============================================================================= */

  /**
   * Aktualisiert den Datensatz der Konferenz $objConferenceDetailed in der Datenbank.
   * Die Kriterien und Topics der Konferenz werden dabei ignoriert.
   *
   * @param ConferenceDetailed $objConferenceDetailed Konferenz-Objekt
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Tom (15.01.05)
   */
  function updateConference($objConferenceDetailed) {
    if (!(is_a($objConferenceDetailed, 'ConferenceDetailed'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   Conference".
                 " SET     name = '%s', homepage = '%s', description = '%s',".
                 "         abstract_submission_deadline = '%s', paper_submission_deadline = '%s',".
                 "         review_deadline = '%s', final_version_deadline = '%s',".
                 "         notification = '%s', conference_start = '%s', conference_end = '%s',".
                 "         min_reviews_per_paper = '%d'".
                 " WHERE   id = '%d'",
                           s2db($objConferenceDetailed->strName),
                           s2db($objConferenceDetailed->strHomepage),
                           s2db($objConferenceDetailed->strDescription),
                           s2db($objConferenceDetailed->strAbstractDeadline),
                           s2db($objConferenceDetailed->strPaperDeadline),
                           s2db($objConferenceDetailed->strReviewDeadline),
                           s2db($objConferenceDetailed->strFinalDeadline),
                           s2db($objConferenceDetailed->strNotification),
                           s2db($objConferenceDetailed->strStart),
                           s2db($objConferenceDetailed->strEnd),
                           s2db($objConferenceDetailed->intMinReviewsPerPaper),
                           s2db($objConferenceDetailed->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateConference', $this->mySql->getLastError());
    }
    $s = sprintf("UPDATE   ConferenceConfig".
                 " SET     default_reviews_per_paper = '%d', min_number_of_papers = '%d',".
                 "         max_number_of_papers = '%d', critical_variance = '%f',".
                 "         auto_activate_account = '%d', auto_open_paper_forum = '%d',".
                 "         auto_add_reviewers = '%d', number_of_auto_add_reviewers = '%d'".
                 " WHERE   id = '%d'",
                           s2db($objConferenceDetailed->intDefaultReviewsPerPaper),
                           s2db($objConferenceDetailed->intMinNumberOfPapers),
                           s2db($objConferenceDetailed->intMaxNumberOfPapers),
                           s2db($objConferenceDetailed->fltCriticalVariance),
                           s2db(b2db($objConferenceDetailed->blnAutoActivateAccount)),
                           s2db(b2db($objConferenceDetailed->blnAutoOpenPaperForum)),
                           s2db(b2db($objConferenceDetailed->blnAutoAddReviewers)),
                           s2db($objConferenceDetailed->intNumberOfAutoAddReviewers),
                           s2db($objConferenceDetailed->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateConference', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Aktualisiert den Datensatz der Person $objPersonDetailed in der Datenbank.
   * Die Rollen werden NICHT in der Datenbank aktualisiert.
   *
   * @param PersonDetailed $objPerson Person, die in der Datenbank aktualisiert werden soll
   * @param int $intConferenceId ID der Konferenz, zu der die Rollen der Person aktualisiert
   *                             werden sollen.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte.
   * @access public
   * @author Sandro (10.01.05)
   */
  function updatePerson($objPersonDetailed, $intConferenceId=false) {
    if (!(is_a($objPersonDetailed, 'PersonDetailed'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   Person".
                 " SET     first_name = '%s', last_name = '%s', email = '%s', title = '%s',".
                 "         affiliation = '%s', street = '%s', city = '%s', postal_code = '%s',".
                 "         state = '%s', country = '%s', phone_number = '%s', fax_number = '%s'".
                 " WHERE   id = '%d'",
                           s2db($objPersonDetailed->strFirstName),
                           s2db($objPersonDetailed->strLastName),
                           s2db($objPersonDetailed->strEmail),
                           s2db($objPersonDetailed->strTitle),
                           s2db($objPersonDetailed->strAffiliation),
                           s2db($objPersonDetailed->strStreet),
                           s2db($objPersonDetailed->strCity),
                           s2db($objPersonDetailed->strPostalCode),
                           s2db($objPersonDetailed->strState),
                           s2db($objPersonDetailed->strCountry),
                           s2db($objPersonDetailed->strPhone),
                           s2db($objPersonDetailed->strFax),
                           s2db($objPersonDetailed->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updatePerson', $this->mySql->getLastError());
    }
    /*
    if (!empty($intConferenceId)) {
      $this->updateRoles($objPersonDetailed, $intConferenceId);
      if ($this->failed()) {
        return $this->error('updatePerson', $this->getLastError());
      }
    }
    */
    return $this->success(true);
  }

  /**
   * Aendert fuer die Person mit der Id $intPersonId in der Datenbank das
   * Passwort.
   *
   * @param int $intPersonId Person, die in der Datenbank aktualisiert werden soll
   * @param string $strPassword Das neue Passwort.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte.
   * @access public
   * @author Jan (31.01.05)
   */
  function updatePersonPassword($intPersonId, $strPassword) {
    $strPassword = sha1($strPassword);
    $s = sprintf("UPDATE   Person".
                 " SET     password = '%s'".
                 " WHERE   id = '%d'",
                           s2db($strPassword),
                           s2db($intPersonId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updatePersonPassword', $this->mySql->getLastError());
    }
    return $this->success($strPassword);
  }


  function acceptRole($intPersonId, $intRoleId, $intConferenceId) {
    $s = sprintf("UPDATE   Role".
                 " SET     state = ''".
                 " WHERE   person_id = '%d'".
                 " AND     role_type = '%d'".
                 " AND     conference_id = '%d'",
                 s2db($intPersonId), s2db($intRoleId), s2db($intConferenceId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('acceptRole', $this->mySql->getLastError());
    }
    return $this->success(true);
  }

  /**
   * Aktualisiert die Co-Autoren (die als Personen in der Datenbank vorkommen)
   * des Papers $objPaperDetailed in der Datenbank.
   *
   * @param PaperDetailed [] $objPaperDetailed Das Objekt des Papers
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Tom (14.01.04)
   */
  function updateCoAuthors($objPaperDetailed) {
    if (!(is_a($objPaperDetailed, 'PaperDetailed'))) {
      return $this->success(false);
    }
    // Co-Autoren loeschen...
    $s = sprintf("DELETE   FROM IsCoAuthorOf".
                 " WHERE   paper_id = '%d'".
                 " AND     person_id IS NOT NULL",
                 s2db($objPaperDetailed->intId));
    $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('updateCoAuthors', $this->mySql->getLastError());
    }
    // Co-Autoren einfuegen...
    for ($i = 0; $i < count($objPaperDetailed->intCoAuthorIds) && !empty($objPaperDetailed->intCoAuthorIds); $i++) {
      if (!empty($objPaperDetailed->intCoAuthorIds[$i])) {
        $s = sprintf("INSERT   INTO IsCoAuthorOf (person_id, paper_id)".
                     " VALUES  ('%d', '%d')",
                     s2db($objPaperDetailed->intCoAuthorIds[$i]), s2db($objPaperDetailed->intId));
        $this->mySql->insert($s);
        if ($this->mySql->failed()) {
          return $this->error('updateCoAuthors', $this->mySql->getLastError());
        }
      }
    }
    return $this->success(true);
  }

  /**
   * Aktualisiert die Namen der Co-Autoren (die nicht als Personen in der Datenbank
   * vorkommen) des Papers $objPaperDetailed in der Datenbank.
   *
   * @param PaperDetailed [] $objPaperDetailed Das Objekt des Papers
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Tom (14.01.04)
   */
  function updateCoAuthorNames($objPaperDetailed) {
    if (!(is_a($objPaperDetailed, 'PaperDetailed'))) {
      return $this->success(false);
    }
    // Co-Autornamen loeschen...
    $s = sprintf("DELETE   FROM IsCoAuthorOf".
                 " WHERE   paper_id = '%d'".
                 " AND     person_id IS NULL",
                 s2db($objPaperDetailed->intId));
    $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('updateCoAuthors', $this->mySql->getLastError());
    }
    // Co-Autornamen einfuegen...
    if (count($objPaperDetailed->intCoAuthorIds) != count($objPaperDetailed->strCoAuthors)) {
      return $this->error('updateCoAuthorNames', 'Co-Author arrays have different length');
    }
    for ($i = 0; $i < count($objPaperDetailed->intCoAuthorIds) && !empty($objPaperDetailed->intCoAuthorIds); $i++) {
      if (empty($objPaperDetailed->intCoAuthorIds[$i]) &&
          !empty($objPaperDetailed->strCoAuthors[$i])) {
        $s = sprintf("INSERT   INTO IsCoAuthorOf (paper_id, name)".
                     " VALUES  ('%d', '%s')",
                     s2db($objPaperDetailed->intId), s2db($objPaperDetailed->strCoAuthors[$i]));
        $this->mySql->insert($s);
        if ($this->mySql->failed()) {
          return $this->error('updateCoAuthorNames', $this->mySql->getLastError());
        }
      }
    }
    return $this->success(true);
  }

  /**
   * Aktualisiert den Datensatz des Artikels mit den Daten des PaperDetailed-Objekts $objPaper.
   * Sorgt nicht (!) dafuer, die aktuelle Dateiversion hochzuladen und aendert
   * dementsprechend auch nichts an mime_type und filename!!
   *
   * @param PaperDetailed $objPaper Artikel, der in der Datenbank aktualisiert werden soll
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (10.01.05)
   */
  function updatePaper($objPaperDetailed) {
    if (!(is_a($objPaperDetailed, 'PaperDetailed'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   Paper".
                 " SET     title = '%s', author_id = '%d', abstract = '%s', ".
                 "         last_edited = '%s', state = '%d'".
                 " WHERE   id = '%d'",
                 s2db($objPaperDetailed->strTitle), s2db($objPaperDetailed->intAuthorId),
                 s2db($objPaperDetailed->strAbstract), s2db(date("Y-m-d H:i:s")),
                 s2db($objPaperDetailed->intStatus), s2db($objPaperDetailed->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updatePaper', $this->mySql->getLastError());
    }
    $this->updateTopicsOfPaper($objPaperDetailed);
    if ($this->failed()) {
      return $this->error('updatePaper', $this->getLastError());
    }
    $this->updateCoAuthors($objPaperDetailed);
    if ($this->failed()) {
      return $this->error('updatePaper', $this->getLastError());
    }
    $this->updateCoAuthorNames($objPaperDetailed);
    if ($this->failed()) {
      return $this->error('updatePaper', $this->getLastError());
    }
    return $this->success(true);
  }

  /**
   * Aktualisiert den Status des Papers $intPaper in der Datenbank.
   *
   * @param int $intPaperId Das Paper, dessen Status aktualisiert werden soll.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (28.01.05)
   */
   function updatePaperStatus($intPaperId) {
     $intNewStatus = false;
     $fltVariance = $this->getVarianceOfPaper($intPaperId);
     if ($this->failed()) {
       return $this->error('updatePaperStatus', $this->getLastError());
     }     
     if (!empty($fltVariance)) {
       if ($this->failed()) {
         return $this->error('updatePaperStatus', $this->getLastError());
       }
       $s = sprintf("SELECT   c.critical_variance, p.state".
                    " FROM    ConferenceConfig AS c".                    
                    " INNER   JOIN Paper AS p".                    
                    " ON      c.id = p.conference_id".
                    " AND     p.id = '%d'",
                              s2db($intPaperId));
       $data = $this->mySql->select($s);
       if ($this->mySql->failed()) {
         return $this->error('updatePaperStatus', $this->mySql->getLastError());
       }
       else if (empty($data)) {
         return $this->error('updatePaperStatus', 'Conference or paper does not exist in database.');
       }
       $fltCriticalVariance = $data[0]['critical_variance'];
       $intPaperStatus      = $data[0]['state'];
       if ($fltVariance >= $fltCriticalVariance) {
       	 $intNewStatus = PAPER_CRITICAL;         
       }
       else {
         $intNewStatus = PAPER_REVIEWED;
       }
     }
     else {
     	$intNewStatus = PAPER_UNREVIEWED;
     }
     if (!empty($intNewStatus) && $intPaperStatus != $intNewStatus &&
         $intPaperStatus != PAPER_ACCEPTED && $intPaperStatus != PAPER_REJECTED) {
       $this->setPaperStatus($intPaperId, $intNewStatus);
       if ($this->failed()) {
         return $this->error('updatePaperStatus', $this->getLastError());
       }
     }
     return $this->success(true);
   }

  /**
   * Setzt den Status des Papers $intPaper in der Datenbank auf $intStatus.
   *
   * @param int $intPaperId Das Paper, dessen Status geaendert werden soll.
   * @param int $intStatus Der neue Statuswert (PAPER_REVIEWED, PAPER_UNREVIEWED,
   *                       PAPER_ACCEPTED, PAPER_REJECTED, PAPER_CRITICAL);
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (28.01.05)
   */
  function setPaperStatus($intPaperId, $intStatus) {
    $s = sprintf("UPDATE   Paper".
                 " SET     state = '%d'".
                 " WHERE   id = '%d'",
                 s2db($intStatus), s2db($intPaperId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('setPaperStatus', $this->mySql->getLastError());
    }
    return $this->success(true);
  }

  /**
   * Setzt den Status des Papers $intPaper in der Datenbank von PAPER_ACCEPTED
   * bzw. PAPER_REJECTED zurueck auf PAPER_REVIEWED, PAPER_UNREVIEWED oder
   * PAPER_CRITICAL, je nachdem, welche Constraints erfuellt sind.
   *
   * @param int $intPaperId Das Paper, dessen Status zurueckgesetzt werden soll.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (28.01.05)
   */
  function resetPaperStatus($intPaperId) {
    $this->setPaperStatus($intPaperId, PAPER_UNREVIEWED);
    if ($this->mySql->failed()) {
      return $this->error('resetPaperStatus', $this->mySql->getLastError());
    }
    $this->updatePaperStatus($intPaperId);
    if ($this->mySql->failed()) {
      return $this->error('resetPaperStatus', $this->mySql->getLastError());
    }
    return $this->success(true);
  }

  /**
   * Laedt die Datei $strFilePath in das Paper $intPaperId hoch.
   *
   * @param int $intPaperId ID des Papers
   * @param int $strFilePath Speicherort der hochzuladenden Datei
   * @return bool true gdw. der Upload korrekt durchgefuehrt werden konnte
   * @access public
   * @author Jan (25.01.05)
   * @todo Delete Add geht vielleicht auch besser
   * @todo Rollback?
   */
  function uploadPaperFile($intPaperId, $strFilePath, $strMimeType, $intFileSize, $strContents) {
    $s = sprintf("UPDATE   Paper".
                 " SET     filename = '%s', mime_type = '%s', ".
                 "         version = version + 1, last_edited = '%s'".
                 " WHERE   id = '%d'",
                 s2db($strFilePath), s2db($strMimeType),
                 s2db(date("Y-m-d H:i:s")), s2db($intPaperId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('uploadPaperFile', $this->mySql->getLastError());
    }
    // remove previous Binary
    $s = sprintf("DELETE   FROM PaperData".
                 " WHERE   paper_id = '%d'",
                           s2db($intPaperId));
    $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('uploadPaperFile', $this->mySql->getLastError());
    }
    // add Paper
    $s = sprintf("INSERT  INTO PaperData (paper_id, filesize, file)".
                 "        VALUES ('%d', '%d', '%s')",
                 s2db($intPaperId),
                 s2db($intFileSize),
                 s2db($strContents));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('uploadPaperFile', $this->mySql->getLastError());
    }
    return $this->success(true);
  }

  /**
   * Aktualisiert den ReviewReport $objReviewDetailed in der Datenbank,
   * sowie mit diesem ReviewReport assoziierten Ratings.
   *
   * @param ReviewDetailed $objReviewDetailed Der ReviewReport
   * @return bool true gdw. die Aktualisierung gelungen ist
   * @access public
   * @author Sandro (25.01.05)
   */
  function updateReviewReport($objReviewDetailed) {
    if (!(is_a($objReviewDetailed, 'ReviewDetailed'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   ReviewReport".
                 " SET     paper_id = '%d', reviewer_id = '%d', summary = '%s',".
                 "         remarks = '%s', confidential = '%s'".
                 " WHERE   id = '%d'",
                 s2db($objReviewDetailed->intPaperId), s2db($objReviewDetailed->intReviewerId),
                 s2db($objReviewDetailed->strSummary), s2db($objReviewDetailed->strRemarks),
                 s2db($objReviewDetailed->strConfidential), s2db($objReviewDetailed->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateReviewReport', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($objReviewDetailed->intRatings) && !empty($objReviewDetailed->intRatings); $i++) {
      $s = sprintf("UPDATE   Rating".
                   " SET     grade = '%d', comment = '%s'".
                   " WHERE   review_id = '%d'".
                   " AND     criterion_id = '%d'",
                   s2db($objReviewDetailed->intRatings[$i]),
                   s2db($objReviewDetailed->strComments[$i]),
                   s2db($objReviewDetailed->intId),
                   s2db($objReviewDetailed->objCriterions[$i]->intId));
      $this->mySql->update($s);
      if ($this->mySql->failed()) {
        return $this->error('updateReviewReport', $this->mySql->getLastError(),
                            'Update operation could not finish, database may be inconsistent!');
      }
    }
    $this->updatePaperStatus($objReviewDetailed->intPaperId);
     if ($this->failed()) {
       return $this->error('updateReviewReport', $this->getLastError());
     }
    return $this->success();
  }

  /**
   * Aktualisiert das Forum $objForum in der Datenbank.
   * @warning Der Messagebaum wird NICHT aktualisiert, sondern nur die Forumsdaten.
   *
   * @param Message $objMessage Die zu aktualisierende Message.
   * @return bool true gdw. die Aktualisierung gelungen ist
   * @access public
   * @author Sandro (27.01.05)
   */
  function updateForum($objForumDetailed) {
    if (!(is_a($objForumDetailed, 'ForumDetailed'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   Forum".
                 " SET     title = '%s', forum_type = '%d'".                 
                 (($objForum->intForumType == FORUM_PAPER) ? " paper_id = '%d'" : "").
                 " WHERE   id = '%d'",
                 s2db($objForum->strTitle),
                 s2db($objForum->intForumType),
                 s2db($objForum->intPaperId),
                 s2db($objForum->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateForum', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Aktualisiert die Message $objMessage in der Datenbank.
   * @warning Der Messagebaum wird NICHT aktualisiert, sondern nur die Daten der Message.
   *
   * @param Message $objMessage Die zu aktualisierende Message.
   * @return bool true gdw. die Aktualisierung gelungen ist
   * @access public
   * @author Sandro (27.01.05)
   */
  function updateMessage($objMessage) {
    if (!(is_a($objMessage, 'Message'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   Message".
                 " SET     sender_id = '%d', send_time = '%s', subject = '%s', text = '%s'".
                 " WHERE   id = '%d'",
                 s2db($objMessage->intSender), s2db(date("Y-m-d H:i:s")),
                 s2db($objMessage->strSubject), s2db($objMessage->strText),
                 s2db($objMessage->intId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateMessage', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Aktualisiert die Kriterien der Konferenz $objConferenceDetailed in der Datenbank.
   * @warning Anders als bei den anderen Updatemethoden wird kein Loeschen und Wiedereinfuegen
   *          durchgefuehrt, weil ansonsten die Auto-ID-Verweise ungueltig wuerden. Stattdessen
   *          findet ein gewoehnliches Update statt, d.h. neu hinzugekommene Kriterien im Array
   *          werden ignoriert und aus dem Array entfernte Kriterien ebenfalls!
   *          Fuer die anderen Faelle gibt es die Funktionen addCriterion und deleteCriterion.
   *
   * @param ConferenceDetailed [] $objConferenceDetailed Das Konferenz-Objekt
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (15.01.04)
   */
  function updateCriterions($objConferenceDetailed) {
    if (!(is_a($objConferenceDetailed, 'ConferenceDetailed'))) {
      return $this->success(false);
    }
    for ($i = 0; $i < count($objConferenceDetailed->objCriterions); $i++) {
      $objCriterion = $objConferenceDetailed->objCriterions[$i];
      $s = sprintf("UPDATE   Criterion".
                   " SET     name = '%s', description = '%s', max_value = '%d',".
                   "         quality_rating = '%f'".
                   " WHERE   id = '%d'",
                   s2db($objCriterion->strName), s2db($objCriterion->strDescription),
                   s2db($objCriterion->intMaxValue), s2db($objCriterion->fltWeight),
                   s2db($objCriterion->intId));
    }
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateCriterions', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Aktualisiert die Themen der Konferenz $objConferenceDetailed in der Datenbank.
   * @warning Anders als bei den anderen Updatemethoden wird kein Loeschen und Wiedereinfuegen
   *          durchgefuehrt, weil ansonsten die Auto-ID-Verweise ungueltig wuerden. Stattdessen
   *          findet ein gewoehnliches Update statt, d.h. neu hinzugekommene Themen im Array
   *          werden ignoriert und aus dem Array entfernte Themen ebenfalls!
   *          Fuer die anderen Faelle gibt es die Funktionen addTopic und deleteTopic.
   *
   * @param ConferenceDetailed [] $objConferenceDetailed Das Konferenz-Objekt
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (15.01.04)
   */
  function updateTopics($objConferenceDetailed) {
    if (!(is_a($objConferenceDetailed, 'ConferenceDetailed'))) {
      return $this->success(false);
    }
    for ($i = 0; $i < count($objConferenceDetailed->objTopics); $i++) {
      $objTopic = $objConferenceDetailed->objTopics[$i];
      $s = sprintf("UPDATE   Topic".
                   " SET     name = '%s'".
                   " WHERE   id = '%d'",
                   s2db($objTopic->strName), s2db($objTopic->intId));
    }
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('updateTopics', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Aktualisiert die Topics des Artikels $objPaperSimple in der Datenbank.
   *
   * @param PaperSimple $objPaperSimple Artikel, dessen Topics in der Datenbank aktualisiert werden
   *                                    sollen
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (15.01.05)
   */
  function updateTopicsOfPaper($objPaperSimple) {
    if (!(is_a($objPaperSimple, 'PaperSimple'))) {
      return $this->success(false);
    }
    $intId = $objPaperSimple->intId;
    // Topics loeschen...
    $s = sprintf("DELETE   FROM IsAboutTopic".
                 " WHERE   paper_id = '%d'",
                 s2db($intId));
    $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('updateTopicsOfPaper', $this->mySql->getLastError());
    }
    if (empty($objPaperSimple->objTopics)) {
      return $this->success();
    }
    // Topics einfuegen...
    $objTopics = $objPaperSimple->objTopics;
    for ($i = 0; $i < count($objTopics) && !empty($objTopics); $i++) {
      if (!empty($objTopics[$i])) {
        $s = sprintf("INSERT   INTO IsAboutTopic (paper_id, topic_id)".
                     " VALUES  ('%d', '%d')",
                     s2db($intId), s2db($objTopics[$i]->intId));
        $this->mySql->insert($s);
        if ($this->mySql->failed()) {
          return $this->error('updateTopicsOfPaper', $this->mySql->getLastError());
        }
      }
    }
    return $this->success();
  }

  /**
   * Aktualisiert die bevorzugten Themen der Person $objPersonAlgorithmic bei der
   * Konferenz mit ID $intConferenceId.
   *
   * @param PersonAlgorithmic $objPersonAlgorithmic Die Person.
   * @param int $intConferenceId Konferenz-ID.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (18.01.05)
   */
  function updatePreferredTopics($objPersonAlgorithmic, $intConferenceId) {
    if (!(is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
      return $this->success(false);
    }
    if (empty($intConferenceId)) {
      return $this->success(false);
    }
    $intPersonId = $objPersonAlgorithmic->intId;
    // Topics loeschen...
    // (umstaendlich, weil bei dieser MySQL-Version kein Join im DELETE erlaubt ist)
    $objTopics = $this->getPreferredTopics($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('updatePreferredTopics', $this->getLastError());
    }
    for ($i = 0; $i < count($objTopics) && !empty($objTopics); $i++) {
      $this->deletePrefersTopic($intPersonId, $objTopics[$i]->intId);
      if ($this->failed()) {
        return $this->error('updatePreferredTopics', $this->getLastError());
      }
    }
    if (empty($objPersonAlgorithmic->objPreferredTopics)) {
      return $this->success();
    }
    $objTopics = $objPersonAlgorithmic->objPreferredTopics;
    // Topics einfuegen...
    for ($i = 0; $i < count($objTopics) && !empty($objTopics); $i++) {
      if (!empty($objTopics[$i])) {
        $this->addPrefersTopic($intPersonId, $objTopics[$i]->intId);
        if ($this->failed()) {
          return $this->error('updatePreferredTopics', $this->getLastError());
        }
      }
    }
    return $this->success();
  }

  /**
   * Aktualisiert die bevorzugten Paper der Person $objPersonAlgorithmic bei der
   * Konferenz mit ID $intConferenceId.
   *
   * @param PersonAlgorithmic $objPersonAlgorithmic Die Person.
   * @param int $intConferenceId Konferenz-ID.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (18.01.05)
   */
  function updatePreferredPapers($objPersonAlgorithmic, $intConferenceId) {
    if (!(is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
      return $this->success(false);
    }
    if (empty($intConferenceId)) {
      return $this->success(false);
    }
    $intPersonId = $objPersonAlgorithmic->intId;
    // Papers loeschen...
    // (umstaendlich, weil bei dieser MySQL-Version kein Join im DELETE erlaubt ist)
    $objPapers = $this->getPreferredPapers($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('updatePreferredPapers', $this->getLastError());
    }
    for ($i = 0; $i < count($objPapers) && !empty($objPapers); $i++) {
      $this->deletePrefersPaper($intPersonId, $objPapers[$i]->intId);
      if ($this->failed()) {
        return $this->error('updatePreferredPapers', $this->getLastError());
      }
    }
    if (empty($objPersonAlgorithmic->objPreferredPapers)) {
      return $this->success();
    }
    $objPapers = $objPersonAlgorithmic->objPreferredPapers;
    // Papers einfuegen...
    for ($i = 0; $i < count($objPapers) && !empty($objPapers); $i++) {
      if (!empty($objPapers[$i])) {
        $this->addPrefersPaper($intPersonId, $objPapers[$i]->intId);
        if ($this->failed()) {
          return $this->error('updatePreferredPapers', $this->getLastError());
        }
      }
    }
    return $this->success();
  }

  /**
   * Aktualisiert die von der Person $objPersonAlgorithmic zum Review abgelehnten Paper bei der
   * Konferenz mit ID $intConferenceId.
   *
   * @param PersonAlgorithmic $objPersonAlgorithmic Die Person.
   * @param int $intConferenceId Konferenz-ID.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (18.01.05)
   */
  function updateDeniedPapers($objPersonAlgorithmic, $intConferenceId) {
    if (!(is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
      return $this->success(false);
    }
    if (empty($intConferenceId)) {
      return $this->success(false);
    }
    $intPersonId = $objPersonAlgorithmic->intId;
    // Papers loeschen...
    // (umstaendlich, weil bei dieser MySQL-Version kein Join im DELETE erlaubt ist)
    $objPapers = $this->getDeniedPapers($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('updateDeniedPapers', $this->getLastError());
    }
    for ($i = 0; $i < count($objPapers) && !empty($objPapers); $i++) {
      $this->deleteDeniesPaper($intPersonId, $objPapers[$i]->intId);
      if ($this->failed()) {
        return $this->error('updateDeniedPapers', $this->getLastError());
      }
    }
    if (empty($objPersonAlgorithmic->objDeniedPapers)) {
      return $this->success();
    }
    $objPapers = $objPersonAlgorithmic->objDeniedPapers;
    // Papers einfuegen...
    for ($i = 0; $i < count($objPapers) && !empty($objPapers); $i++) {
      if (!empty($objPapers[$i])) {
        $this->addDeniesPaper($intPersonId, $objPapers[$i]->intId);
        if ($this->failed()) {
          return $this->error('updateDeniedPapers', $this->getLastError());
        }
      }
    }
    return $this->success();
  }

  /**
   * Aktualisiert die fuer die Person $objPersonAlgorithmic ausgeschlossenen Paper bei der
   * Konferenz mit ID $intConferenceId.
   *
   * @param PersonAlgorithmic $objPersonAlgorithmic Die Person.
   * @param int $intConferenceId Konferenz-ID.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (18.01.05)
   */
  function updateExcludedPapers($objPersonAlgorithmic, $intConferenceId) {
    if (!(is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
      return $this->success(false);
    }
    if (empty($intConferenceId)) {
      return $this->success(false);
    }
    $intPersonId = $objPersonAlgorithmic->intId;
    // Papers loeschen...
    // (umstaendlich, weil bei dieser MySQL-Version kein Join im DELETE erlaubt ist)
    $objPapers = $this->getExcludedPapers($intPersonId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('updateExcludedPapers', $this->getLastError());
    }
    for ($i = 0; $i < count($objPapers) && !empty($objPapers); $i++) {
      $this->deleteExcludesPaper($intPersonId, $objPapers[$i]->intId);
      if ($this->failed()) {
        return $this->error('updateExcludedPapers', $this->getLastError());
      }
    }
    if (empty($objPersonAlgorithmic->objExcludedPapers)) {
      return $this->success();
    }
    $objPapers = $objPersonAlgorithmic->objExcludedPapers;
    // Papers einfuegen...
    for ($i = 0; $i < count($objPapers) && !empty($objPapers); $i++) {
      if (!empty($objPapers[$i])) {
        $this->addExcludesPaper($intPersonId, $objPapers[$i]->intId);
        if ($this->failed()) {
          return $this->error('updateExcludedPapers', $this->getLastError());
        }
      }
    }
    return $this->success();
  }

/**
   * Aktualisiert die Artikel- und Themen-Praeferenzen, die durch das Mapping
   * $objReviewerAttitude angegeben sind.
   *
   * @param ReviewerAttitude $objReviewerAttitude Die Abbildung von Themen- und
                                                  Paper-IDs auf Praeferenzwerte
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (24.01.05)
   */
  function updateReviewerAttitude($objReviewerAttitude) {
    if (!(is_a($objReviewerAttitude, 'ReviewerAttitude'))) {
      return $this->success(false);
    }
    $objPapers = $this->getPapersOfConference($objReviewerAttitude->intConferenceId);
    if ($this->failed()) {
      return $this->error('updateReviewerAttitude', $this->getLastError());
    }
    $objTopics = $this->getTopicsOfConference($objReviewerAttitude->intConferenceId);
    if ($this->failed()) {
      return $this->error('updateReviewerAttitude', $this->getLastError());
    }
    foreach ($objPapers as $objPaper) {
      $this->deletePrefersPaper($objReviewerAttitude->intReviewerId, $objPaper->intId);
      if ($this->failed()) {
        return $this->error('updateReviewerAttitude', $this->getLastError());
      }
      $this->deleteDeniesPaper($objReviewerAttitude->intReviewerId, $objPaper->intId);
      if ($this->failed()) {
        return $this->error('updateReviewerAttitude', $this->getLastError());
      }
      $this->deleteExcludesPaper($objReviewerAttitude->intReviewerId, $objPaper->intId);
      if ($this->failed()) {
        return $this->error('updateReviewerAttitude', $this->getLastError());
      }
      if ($objReviewerAttitude->getPaperAttitude($objPaper->intId) == ATTITUDE_PREFER) {
        $this->addPrefersPaper($objReviewerAttitude->intReviewerId, $objPaper->intId);
        if ($this->failed()) {
          return $this->error('updateReviewerAttitude', $this->getLastError());
        }
      }
      else if ($objReviewerAttitude->getPaperAttitude($objPaper->intId) == ATTITUDE_DENY) {
        $this->addDeniesPaper($objReviewerAttitude->intReviewerId, $objPaper->intId);
        if ($this->failed()) {
          return $this->error('updateReviewerAttitude', $this->getLastError());
        }
      }
      else if ($objReviewerAttitude->getPaperAttitude($objPaper->intId) == ATTITUDE_EXCLUDE) {
        $this->addExcludesPaper($objReviewerAttitude->intReviewerId, $objPaper->intId);
        if ($this->failed()) {
          return $this->error('updateReviewerAttitude', $this->getLastError());
        }
      }
    }
    foreach ($objTopics as $objTopic) {
      $this->deletePrefersTopic($objReviewerAttitude->intReviewerId, $objTopic->intId);
      if ($this->failed()) {
        return $this->error('updateReviewerAttitude', $this->getLastError());
      }
      if ($objReviewerAttitude->getTopicAttitude($objTopic->intId) == ATTITUDE_PREFER) {
        $this->addPrefersTopic($objReviewerAttitude->intReviewerId, $objTopic->intId);
        if ($this->failed()) {
          return $this->error('updateReviewerAttitude', $this->getLastError());
        }
      }
    }
    return $this->success(true);
  }


  // ---------------------------------------------------------------------------
  // Definition der Insert-Funktionen
  // ---------------------------------------------------------------------------

  /**
   * Legt eine neue Konferenz an.
   *
   * @param string $strAbstractDeadline Deadline fuer die Einsendung der Abstracts
   * @param string $strPaperDeadline    Deadline fuer die Einsendung der Paper
   * @param string $strReviewDeadline   Deadline fuer die Reviews
   * @param string $strFinalDeadline    Deadline fuer die Einsendung der finale Version der Paper
   * @param string $strNotification     Bekanntgabe der angenommenen Paper
   *
   * @access public
   * @author Daniel (31.12.04), ueberarbeitet von Tom (13.01.05)
   */
  function addConference($strName, $strHomepage, $strDescription, $strAbstractDeadline,
                         $strPaperDeadline, $strReviewDeadline, $strFinalDeadline,
                         $strNotification, $strConferenceStart, $strConferenceEnd,
                         $intMinReviews, $intDefaultReviews, $intMinPapers, $intMaxPapers,
                         $fltVariance, $blnAutoActAccount, $blnAutoPaperForum,
                         $blnAutoAddReviewer, $intNumAutoAddReviewer,
                         $strTopics=array(), $strCriterions=array(), $strCritDescripts=array(),
                         $intCritMaxVals=array(), $fltCritWeights=array()) {
    $this->mySql->startTransaction();
    if ($this->mySql->failed()) { // Fehler bei Start Transaction?
      return $this->error('addConference', 'Fatal error: Transaction start failed!',
                          $this->mySql->getLastError());
    }
    $s = sprintf("INSERT INTO Conference (name, homepage, description, abstract_submission_deadline,".
        "                                 paper_submission_deadline, review_deadline,".
        "                                 final_version_deadline, notification, conference_start,".
        "                                 conference_end, min_reviews_per_paper)".
        "                VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                         s2db($strName), s2db($strHomepage), s2db($strDescription), s2db($strAbstractDeadline),
                         s2db($strPaperDeadline), s2db($strReviewDeadline), s2db($strFinalDeadline),
                         s2db($strNotification), s2db($strConferenceStart), s2db($strConferenceEnd),
                         s2db($intMinReviews));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      $strError = $this->mySql->getLastError();
      $this->mySql->rollback();
      if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
        return $this->error('addConference', 'Fatal error: Rollback failed!',
                            $this->mySql->getLastError());
      }
      return $this->error('addConference', $strError);
    }
    $s = sprintf("INSERT INTO ConferenceConfig (id, default_reviews_per_paper,".
         "   min_number_of_papers, max_number_of_papers, critical_variance, ".
         "   auto_activate_account, auto_open_paper_forum, auto_add_reviewers,".
         "   number_of_auto_add_reviewers)".
         " VALUES ('%d', '%d', '%d', '%d', '%f', '%d', '%d', '%d', '%d')",
           s2db($intId), s2db($intDefaultReviews), s2db($intMinPapers),
           s2db($intMaxPapers), s2db($fltVariance), s2db(b2db($blnAutoActAccount)),
           s2db(b2db($blnAutoPaperForum)), s2db(b2db($blnAutoAddReviewer)),
           s2db($intNumAutoAddReviewer));
    $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      $strError = $this->mySql->getLastError();
      $this->mySql->rollback();
      if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
        return $this->error('addConference', 'Fatal error: Rollback failed!',
                            $this->mySql->getLastError());
      }
      return $this->error('addConference', $strError);
    }
    if (!empty($strTopics)) {
      for ($i = 0; $i < count($strTopics); $i++) {
        $this->addTopic($intId, $strTopics[$i]);
        if ($this->failed()) {
          $strError = $this->getLastError();
          $this->mySql->rollback();
          if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
            return $this->error('addConference', 'Fatal error: Rollback failed!',
                                $this->mySql->getLastError());
          }
          return $this->error('addConference', $strError);
        }
      }
    }
    if (!empty($strCriterions)) {
      for ($i = 0; $i < count($strCriterions); $i++) {
        $this->addCriterion($intId, $strCriterions[$i], $strCritDescripts[$i],
                            $intCritMaxVals[$i], $fltCritWeights[$i]);
        if ($this->failed()) {
          $strError = $this->getLastError();
          $this->mySql->rollback();
          if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
            return $this->error('addConference', 'Fatal error: Rollback failed!',
                                $this->mySql->getLastError());
          }
          return $this->error('addConference', $strError);
        }
      }
    }
    $this->mySql->commit();
    if ($this->mySql->failed()) { // Fehler beim Commit? => fatal!
      return $this->error('addConference', 'Fatal error: Commit failed!',
                          $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Person ein.
   *
   * @param string $strFirstname    Vorname(n) der Person
   * @param string $strLastname     Nachname der Person
   * @param string $strEmail        Email-Adresse der Person (Login fuer den Account)
   * @param string $strTitle        Titel der Person
   * @param string $strAffiliation  Zur Person zugehoerige Einrichtung oder Gruppe
   * @param string $strStreet       Strasse und Hausnummer des Wohnsitzes
   * @param string $strCity         Wohnort der Person
   * @param string $strPostalCode   Postleitzahl oder ZIP-Code des Wohnsitzes
   * @param string $strState        Staat oder Bundesland des Wohnsitzes
   * @param string $strcountry      Land des Wohnsitzes
   * @param string $strPhone        Telefonnummer der Person
   * @param string $strFax          Faxnummer der Person
   * @param string $strPassword     Passwort des Accounts (unverschluesselt zu uebergeben)
   * @return int ID der erzeugten Person oder false, falls ein Fehler
   *             aufgetreten ist.
   * @access public
   * @author Sandro, Tom (17.12.04)
   */
  function addPerson($strFirstname, $strLastname, $strEmail, $strTitle,
                     $strAffiliation, $strStreet, $strCity, $strPostalCode,
                     $strState, $strcountry, $strPhone, $strFax, $strPassword) {
    $s = sprintf("INSERT  INTO Person (first_name, last_name, title, affiliation, email,".
                 "  street, postal_code, city, state, country,".
                 "  phone_number, fax_number, password)".
                 "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',".
                 "  '%s', '%s', '%s', '%s', '%s')",
                s2db($strFirstname), s2db($strLastname), s2db($strTitle),
                s2db($strAffiliation), s2db($strEmail), s2db($strStreet),
                s2db($strPostalCode), s2db($strCity), s2db($strState),
                s2db($strcountry), s2db($strPhone), s2db($strFax),
                s2db(sha1($strPassword)));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addPerson', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt der Person $intPersonId die Rolle $intRole hinzu.
   *
   * @param int $intPersonId     Personen-ID
   * @param int $intRole         Rollen-Enum
   * @param int $intConferenceId Konferenz-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function addRole($intPersonId, $intRoleType, $intConferenceId, $blnAccepted=true) {
    if ($blnAccepted) {
      $s = sprintf("INSERT INTO Role (conference_id, person_id, role_type)".
                   "VALUES ('%d', '%d', '%d')",
                   s2db($intConferenceId), s2db($intPersonId), s2db($intRoleType));
    }
    else {
      $s = sprintf("INSERT INTO Role (conference_id, person_id, role_type, state)".
                   "VALUES ('%d', '%d', '%d', '1')",
                   s2db($intConferenceId), s2db($intPersonId), s2db($intRoleType));
    }
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addRole', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt dem Paper $intPaperId den Co-Autor $intCoAuthorId hinzu.
   *
   * @param int $intPaperId    Paper-Id
   * @param int $intCoAuthorId Co-Autor-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function addCoAuthor($intPaperId, $intCoAuthorId) {
    $s = sprintf("INSERT  INTO IsCoAuthorOf (person_id, paper_id)".
                 "VALUES ('%d', '%d')",
                  s2db($intCoAuthorId), s2db($intPaperId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addCoAuthor', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt dem Paper $intPaperId den Namen $strName eines Co-Autoren hinzu,
   * der nicht als Person im System vorkommt.
   *
   * @param int $intPaperId Paper-ID
   * @param str $strName    Name des Co-Autors
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function addCoAuthorName($intPaperId, $strName) {
    $s = sprintf("INSERT  INTO IsCoAuthorOf (paper_id, name)".
                 "VALUES ('%d', '%s')",
                  s2db($intPaperId), s2db($strName));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addCoAuthorName', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt ein Paper (mit den Namen der Co-Autoren) hinzu.
   *
   * @param int $intConferenceId Konferenz-ID
   * @param int $intAuthorId     Autor-ID
   * @param str $strTitle        Titel
   * @param str $strAbstract     Text des Abstracts
   * @param str $strCoAuthors[]  Namen der Co-Autoren
   * @param int $intTopicIds[]   IDs der behandelten Themen
   * @return int ID des erzeugten Papers
   *
   * @access public
   * @author Tom (26.12.04)
   */
  function addPaper($intConferenceId, $intAuthorId, $strTitle, $strAbstract,
                    $strCoAuthors, $intTopicIds) {      
    $this->mySql->startTransaction();
    if ($this->mySql->failed()) { // Fehler bei Start Transaction?
      return $this->error('addPaper', 'Fatal error: Transaction start failed!',
                          $this->mySql->getLastError());
    }                    	
    $s = sprintf("INSERT  INTO Paper (conference_id, author_id, title, abstract, filename, ".
                 "mime_type, version, state, last_edited)".
                 "VALUES ('%d', '%d', '%s', '%s', '', '', '1', '%d', '%s')",
                 s2db($intConferenceId), s2db($intAuthorId), s2db($strTitle), s2db($strAbstract),
                 PAPER_UNREVIEWED, s2db(date("Y-m-d H:i:s")));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      $strError = $this->mySql->getLastError();    
      $this->mySql->rollback();
      if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
        return $this->error('addPaper', 'Fatal error: Rollback failed!',
                            $this->mySql->getLastError());
      }
      return $this->error('addPaper', $strError);
    }
    for ($i = 0; $i < count($strCoAuthors) && !empty($strCoAuthors); $i++) {
      $this->addCoAuthorName($intId, $strCoAuthors[$i]);
      if ($this->failed()) { // Rollback ausfuehren
        $strError = $this->getLastError();
        $this->mySql->rollback();
        if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
          return $this->error('addPaper', 'Fatal error: Rollback failed!',
                              $this->mySql->getLastError());
        }
        return $this->error('addPaper', $strError);
      }
    }
    for ($i = 0; $i < count($intTopicIds) && !empty($intTopicIds); $i++) {
      $this->addIsAboutTopic($intId, $intTopicIds[$i]);
      if ($this->failed()) { // Rollback ausfuehren
        $strError = $this->getLastError();
        $this->mySql->rollback();
        if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
          return $this->error('addPaper', 'Fatal error: Rollback failed!',
                              $this->mySql->getLastError());
        }
        return $this->error('addPaper', $strError);
      }
    }
    $this->addExcludesPaper($intAuthorId, $intId);
    if ($this->failed()) { // Rollback ausfuehren
      $strError = $this->getLastError();
      $this->mySql->rollback();
      if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
        return $this->error('addPaper', 'Fatal error: Rollback failed!',
                            $this->mySql->getLastError());
      }
      return $this->error('addPaper', $strError);
    }
    $this->mySql->commit();
    if ($this->mySql->failed()) { // Fehler beim Commit? => fatal!
      return $this->error('addPaper', 'Fatal error: Commit failed!',
                          $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle ReviewReport ein.
   *
   * @param int $intPaperId          ID des Papers, das bewertet wird
   * @param int $intReviewerId       ID des Reviewers
   * @param string $strSummary       Zusammenfassender Text fuer die Bewertung
   * @param string $strRemarks       Anmerkungen fuer den Autoren
   * @param string $strConfidential  Vertrauliche Anmerkungen fuer das Kommitee
   * @return int ID des erzeugten Review-Reports
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addReviewReport($intPaperId, $intReviewerId, $strSummary,
                           $strRemarks, $strConfidential) {
    $s = sprintf("INSERT  INTO ReviewReport (paper_id, reviewer_id, summary, remarks, confidential)".
                 "VALUES ('%d', '%d', '%s', '%s', '%s')",
                 s2db($intPaperId), s2db($intReviewerId), s2db($strSummary), s2db($strRemarks), s2db($strConfidential));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addReviewReport', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Rating ein.
   *
   * @param int $intReviewId     ID des Review-Reports, der das Rating beinhaltet
   * @param int $intCriterionId  ID des Bewertungskriteriums
   * @param int $intGrade        Note, Auspraegung der Bewertung
   * @param string $strComment   Anmerkung zur Bewertung in dem Kriterium
   * @return int ID des erzeugten Ratings
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addRating($intReviewId, $intCriterionId, $intGrade, $strComment) {
    $s = sprintf("INSERT  INTO Rating (review_id, criterion_id, grade, comment)".
                 "VALUES ('%d', '%d', '%d', '%s')",
                 s2db($intReviewId), s2db($intCriterionId), s2db($intGrade), s2db($strComment));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addCriterion', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Erstellt einen neuen Review-Report-Datensatz zum Paper $intPaperId und
   * Reviewer $intReview in der Datenbank mit den angegebenen Werten, sowie die
   * mit diesem Review-Report assoziierten Ratings.
   * Aktualisiert ebenfalls den Status des bewerteten Papers.
   *
   * @param int $intPaperId          ID des Papers, das bewertet wird
   * @param int $intReviewerId       ID des Reviewers
   * @param string $strSummary       Zusammenfassender Text fuer die Bewertung
   * @param string $strRemarks       Anmerkungen fuer den Autoren
   * @param string $strConfidential  Vertrauliche Anmerkungen fuer das Kommitee
   * @param int [] $intRatings       Bewertungen in den einzelnen Kriterien
   * @param int [] $strComments      Kommentare zu den einzelnen Kriterien
   * @param Criterion [] $objCriterions Die zu bewertenden Kriterien
   * @return int ID des erzeugten Review-Reports
   *
   * @access public
   * @author Sandro (28.01.05)
   * @todo Rollback bei Fehlern beim Eintragen der Ratings
   */

   function createNewReviewReport($intPaperId, $intReviewerId, $strSummary,
                           $strRemarks, $strConfidential,
                           $intRatings, $strComments, $objCriterions) {
     $intReviewId = $this->addReviewReport($intPaperId, $intReviewerId,
                                           $strSummary, $strRemarks, $strConfidential);
     if ($this->failed()) {
        return $this->error('createNewReviewReport', $this->getLastError());
     }
     for ($i = 0; $i < count($objCriterions) && !empty($objCriterions); $i++) {
        $this->addRating($intReviewId, $objCriterions[$i]->intId,
                         $intRatings[$i], $strComments[$i]);
        if ($this->failed()) {
          return $this->error('createNewReviewReport', $this->getLastError());
        }
     }
     $this->updatePaperStatus($intPaperId);
     if ($this->failed()) {
       return $this->error('createNewReviewReport', $this->getLastError());
     }
     return $this->success($intReviewId);
   }

  /**
   * Fuegt einen Datensatz in die Tabelle Forum ein.
   *
   * @param int $intConferenceId  ID der Konferenz, fuer die das Forum angelegt wird
   * @param string $strTitle      Bezeichnung des Forums
   * @param int $intForumType     Art des Forums (FORUM_PUBLIC, FORUM_PRIVATE, FORUM_PAPER)
   * @param int $intPaperId       ID des assoziierten Artikels bei Artikelforen (sonst: false)
   * @return int ID des erzeugten Forums
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addForum($intConferenceId, $strTitle, $intForumType, $intPaperId=false) {
    if ($intForumType != FORUM_PAPER) {
      $intPaperId = false;
    }
    $s = sprintf("INSERT  INTO Forum (conference_id, title, forum_type, paper_id)".
                 "VALUES ('%d', '%s', '%d', '%d')",
                 s2db($intConferenceId), s2db($strTitle), s2db($intForumType), s2db($intPaperId));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addForum', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Message ein.
   * @warning Zum Erzeugen von Threads wird der Parameter $intReplyTo ausgelassen.
   *
   * @param string $strSubject   Betreff der Message
   * @param string $strText      Inhalt der Message
   * @param string $intSenderId  ID des Erstellers der Message
   * @param int $intForumId      ID des Forums, in das die Message eingefuegt wird
   * @param int $intReplyTo      ID der Nachricht, auf welche die Message antwortet
   * @return int ID der erzeugten Message
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addMessage($strSubject, $strText, $intSenderId, $intForumId, $intReplyTo=false) {
    if (empty($intReplyTo)) {
      $s = sprintf("INSERT  INTO Message (subject, text, sender_id, forum_id, send_time)".
                   "VALUES ('%s', '%s', '%d', '%d', '%s')",
                   s2db($strSubject), s2db($strText), s2db($intSenderId), s2db($intForumId),
                   s2db(date("Y-m-d H:i:s")));
    }
    else {
      $s = sprintf("INSERT  INTO Message (subject, text, sender_id, forum_id, send_time, reply_to)".
                   "VALUES ('%s', '%s', '%d', '%d', '%s', '%d')",
                   s2db($strSubject), s2db($strText), s2db($intSenderId), s2db($intForumId),
                   s2db(date("Y-m-d H:i:s")), s2db($intReplyTo));
    }
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addMessage', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Criterion ein.
   *
   * @param int $intConferenceId    ID der Konferenz, fuer die das Kriterium angelegt wird
   * @param string $strName         Bezeichnung des Bewertungskriteriums
   * @param string $strDescription  Beschreibungstext fuer das Kriterium
   * @param int $intMaxValue        Groesster zu vergebender Wert fuer die Bewertung in
   *                                diesem Kriterium (Wertespektrum: 0..$intMaxValue)
   * @param float $fltWeight        Gewichtung des Kriteriums (Wert aus dem Intervall [0, 1])
   * @return int ID des erzeugten Bewertungskriteriums
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addCriterion($intConferenceId, $strName, $strDescription, $intMaxValue, $fltWeight) {
    $s = sprintf("INSERT  INTO Criterion (conference_id, name, description, max_value, quality_rating)".
                 "VALUES ('%d', '%s', '%s', '%d', '%d')",
                 s2db($intConferenceId), s2db($strName), s2db($strDescription), s2db($intMaxValue), s2db(round($fltWeight * 100)));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addCriterion', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Topic ein.
   *
   * @param int $intConferenceId  ID der Konferenz, fuer die das Topic angelegt wird
   * @param string $strName       Bezeichnung des Topics
   * @return int ID des erzeugten Topics
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addTopic($intConferenceId, $strName) {
    $s = sprintf("INSERT  INTO Topic (conference_id, name)".
                 "VALUES ('%d', '%s')",
                 s2db($intConferenceId), s2db($strName));
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addTopic', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle IsAboutTopic ein.
   *
   * @param int $intPaperId  ID des Papers
   * @param int $intTopicId  ID des behandelten Topics
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Sandro (18.12.04)
   */
  function addIsAboutTopic($intPaperId, $intTopicId) {
    $s = sprintf("INSERT  INTO IsAboutTopic (paper_id, topic_id)".
                 "VALUES ('%d', '%d')",
                 s2db($intPaperId), s2db($intTopicId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addIsAboutTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt einen Datensatz in die Tabelle PrefersTopic ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intTopicId   ID des bevorzugten Topics
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Sandro (18.12.04)
   */
  function addPrefersTopic($intPersonId, $intTopicId) {
    $s = sprintf("INSERT  INTO PrefersTopic (person_id, topic_id)".
                 "VALUES ('%d', '%d')",
                 s2db($intPersonId), s2db($intTopicId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addPrefersTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt einen Datensatz in die Tabelle PrefersPaper ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intPaperId   ID des bevorzugten Papers
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Sandro (18.12.04)
   */
  function addPrefersPaper($intPersonId, $intPaperId) {
    $s = sprintf("INSERT  INTO PrefersPaper (person_id, paper_id)".
                 "VALUES ('%d', '%d')",
                 s2db($intPersonId), s2db($intPaperId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addPrefersPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt einen Datensatz in die Tabelle DeniesPaper ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intPaperId   ID des abgelehnten Papers
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Sandro (18.12.04)
   */
  function addDeniesPaper($intPersonId, $intPaperId) {
    $s = sprintf("INSERT  INTO DeniesPaper (person_id, paper_id)".
                 "VALUES ('%d', '%d')",
                 s2db($intPersonId), s2db($intPaperId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addDeniesPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt einen Datensatz in die Tabelle ExcludesPaper ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intPaperId   ID des ausgeschlossenen Papers
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Sandro (18.12.04)
   */
  function addExcludesPaper($intPersonId, $intPaperId) {
    $s = sprintf("INSERT  INTO ExcludesPaper (person_id, paper_id)".
                 "VALUES ('%d', '%d')",
                 s2db($intPersonId), s2db($intPaperId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addExcludesPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Distribution ein.
   *
   * @param int $intReviewerId  ID der Person
   * @param int $intPaperId     ID des Papers
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (28.01.05)
   */
  function addDistribution($intReviewerId, $intPaperId) {
    $s = sprintf("INSERT  INTO Distribution (reviewer_id, paper_id)".
                 "VALUES ('%d', '%d')",
                 s2db($intReviewerId), s2db($intPaperId));
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addDistribution', $this->mySql->getLastError());
    }
    return $this->success();
  }

  // ---------------------------------------------------------------------------
  // Definition der Delete-Funktionen
  // ---------------------------------------------------------------------------

  /**
   * Loescht die Konferenz mit der ID $intConferenceId.
   *
   * @param int $intConferenceId Konferenz-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteConference($intConferenceId) {
    $s = sprintf("DELETE   FROM Conference".
                 " WHERE   id = '%d'",
                           s2db($intConferenceId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteConference', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Deaktiviert den Account der Person mit der ID $intPersonId.
   *
   * @param int $intPersonId Personen-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deactivateAccount($intPersonId) {
    $s = sprintf("UPDATE   Person".
                 " SET     password = ''".
                 " WHERE   id = '%d'",
                           s2db($intPersonId));
    $result = $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('deactivateAccount', $this->mySql->getLastError());
    }
    return $this->success();
  }
  
  /**
   * Loescht die Person mit der ID $intPersonId.
   *
   * @param int $intPersonId ID der zu loeschenden Person.
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Sandro (06.02.05)
   */
  function deletePerson($intPersonId) {
    $s = sprintf("DELETE   FROM Person".
                 " WHERE   id = '%d'",
                 s2db($intPersonId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deletePerson', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Loescht das Paper mit der ID $intPaperId.
   *
   * @param int $intPaperId Paper-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deletePaper($intPaperId) {
    $s = sprintf("DELETE   FROM Paper".
                 " WHERE   id = '%d'",
                           s2db($intPaperId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deletePaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Loescht die Rolle $intRoleType der Person $intPersonId fuer die Konferenz
   * $intConferenceId.
   *
   * @param int $intPersonId     Personen-ID
   * @param int $intRoleType     Rollen-Enum
   * @param int $intConferenceId Konferenz-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteRole($intPersonId, $intRoleType, $intConferenceId) {
    $s = sprintf("DELETE   FROM Role".
                 " WHERE   conference_id = '%d'".
                 " AND     person_id = '%d'".
                 " AND     role_type = '%d'",
                           s2db($intConferenceId),
                           s2db($intPersonId),
                           s2db($intRoleType));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteRole', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Loescht den Co-Autor $intCoAuthorId vom Paper $intPaperId.
   *
   * @param int $intPaperId    Paper-Id
   * @param int $intCoAuthorId Co-Autor-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteCoAuthor($intPaperId, $intCoAuthorId) {
    $s = sprintf("DELETE   FROM IsCoAuthorOf".
                 " WHERE   person_id = '%d'".
                 " AND     paper_id = '%d'",
                           s2db($intCoAuthorId),
                           s2db($intPaperId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteCoAuthor', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Loescht den Co-Autor mit Namen $strName vom Paper $intPaperId.
   *
   * @param int $intPaperId Paper-Id
   * @param int $strName    Name des Co-Autors
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteCoAuthorName($intPaperId, $strName) {
    $s = sprintf("DELETE   FROM IsCoAuthorOf".
                 " WHERE   paper_id = '%d'".
                 " AND     name = '%s'",
                           s2db($intPaperId),
                           s2db($strName));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteCoAuthorName', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteReviewReport($intReviewId) {
    $s = sprintf("DELETE   FROM Reviewreport".
                 " WHERE   id = '%d'",
                           s2db($intReviewId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteReviewReport', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteRating($intReviewId, $intCriterionId) {
    $s = sprintf("DELETE   FROM Rating".
                 " WHERE   review_id = '%d'".
                 " AND     criterion_id = '%d'",
                           s2db($intReviewId),
                           s2db($intCriterionId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteRating', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteForum($intForumId) {
    $s = sprintf("DELETE   FROM Forum".
                 " WHERE   id = '%d'",
                           s2db($intForumId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteForum', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteMessage($intMessageId) {
    // get reply_to
    $s = sprintf("SELECT   reply_to".
                 "FROM     Message".
                 " WHERE   id = '%d'",
                           s2db($intMessageId));
    $result = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteMessage', $this->mySql->getLastError());
    }
    $reply_to = $result[0]['reply_to'];
    // Delete Msg
    $s = sprintf("DELETE   FROM Message".
                 " WHERE   id = '%d'",
                           s2db($intMessageId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteMessage', $this->mySql->getLastError());
    }
    // replyTo Anpassen
    $s = sprintf("UPDATE   Message".
                 " SET     reply_to = '%d'".
                 " WHERE   reply_to = '%d'",
                 s2db($reply_to),
                 s2db($intMessageId));
    $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteMessage', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteCriterion($intCriterionId) {
    $s = sprintf("DELETE   FROM Criterion".
                 " WHERE   id = '%d'",
                           s2db($intCriterionId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteCriterion', $this->mySql->getLastError());
    }
    return $this->success();

  }

  /**
   */
  function deleteTopic($intTopicId) {
    $s = sprintf("DELETE   FROM Topic".
                 " WHERE   id = '%d'",
                           s2db($intTopicId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteIsAboutTopic($intPaperId, $intTopicId) {
    $s = sprintf("DELETE   FROM IsAboutTopic".
                 " WHERE   paper_id = '%d'".
                 " AND     topic_id = '%d'",
                           s2db($intPaperId),
                           s2db($intTopicId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteIsAboutTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deletePrefersTopic($intPersonId, $intTopicId) {
    $s = sprintf("DELETE   FROM PrefersTopic".
                 " WHERE   person_id = '%d'".
                 " AND     topic_id = '%d'",
                           s2db($intPersonId),
                           s2db($intTopicId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deletePrefersTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deletePrefersPaper($intPersonId, $intPaperId) {
    $s = sprintf("DELETE   FROM PrefersPaper".
                 " WHERE   person_id = '%d'".
                 " AND     paper_id = '%d'",
                           s2db($intPersonId),
                           s2db($intPaperId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deletePrefersPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteDeniesPaper($intPersonId, $intPaperId) {
    $s = sprintf("DELETE   FROM DeniesPaper".
                 " WHERE   person_id = '%d'".
                 " AND     paper_id = '%d'",
                           s2db($intPersonId),
                           s2db($intPaperId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteDeniesPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteExcludesPaper($intPersonId, $intPaperId) {
    $s = sprintf("DELETE   FROM ExcludesPaper".
                 " WHERE   person_id = '%d'".
                 " AND     paper_id = '%d'",
                           s2db($intPersonId),
                           s2db($intPaperId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteExcludesPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteDistribution($intReviewerId, $intPaperId) {
    $s = sprintf("DELETE   FROM Distribution".
                 " WHERE   reviewer_id = '%d'".
                 " AND     paper_id = '%d'",
                           s2db($intReviewerId),
                           s2db($intPaperId));
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteDistribution', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Loescht ALLE Zuteilungen des Reviewers mit ID $intReviewerId betreffend
   * Paper der Konferenz mit der ID $intConferenceId.
   *
   * @param int @intReviewerId Reviewer-ID.
   * @param int @intConferenceId Konferenz-ID.
   * @return konstant true
   */
  function deleteReviewerDistribution($intReviewerId, $intConferenceId) {
    $objPapers = $this->getPapersOfReviewer($intReviewerId, $intConferenceId);
    if ($this->failed()) {
      return $this->error('deleteReviewerDistribution', $this->getLastError());
    }
    for ($i = 0; $i < count($objPapers); $i++) {
      $this->deleteDistribution($intReviewerId, $objPapers[$i]->intId);
      if ($this->failed()) {
        return $this->error('deleteReviewerDistribution', $this->getLastError());
      }
    }
    return $this->success();
  }
  // end of class DBAccess
}

?>