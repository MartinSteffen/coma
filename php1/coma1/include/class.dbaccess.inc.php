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

/* =============================================================================
WICHTIGE NEUIGKEITEN 14.01.05 / 18.01.05:
-----------------------------------------
- Objekt PersonAlgorithmic hinzugefuegt; entsprechende DBAccess-Methonden
- Bitte bei ALLEN Datentypen (nicht nur Strings) in SQL-Statements immer
  Anfuehrungszeichen verwenden, weil sonst leere Integer (=false) als Nullstring
  in den SQL-String eingesetzt werden mit der Folge, dass Anfragen wie
    SELECT * FROM tab WHERE id =
  zustandekommen können, was einen SQL-Fehler und damit einen error erzeugt
  (hier nicht erwuenscht).
  Stattdessen sollte nach der SQL-Anfrage ggf. die Ergebnismenge auf EMPTY
  geprueft werden. => Anfrage wie
    SELECT * FROM tab WHERE id = ''
  liefert leeres Ergegnisarray (ok).
  Sieh Dir mal die zweite SELECT-Anfrage in getPerson an; so sollte es sein.
  Sieht -- finde ich -- immer noch besser aus als unsere Lösung mit \'.
  Ich habe es (hoffentlich) ueberall gefixt. *stoehn* (Ich komme zu nichts,
  weil ich permanent irgendwelche bloeden Fehler finde, die z.T. aus unserer
  Unwissenheit entstanden sind; bei der DBAccess-Groesse ist es nur immer ein
  Riesenaufwand, solche Aenderungen durchzufuehren...)
- Ausserdem habe ich so meine liebe Mueh und Not mit den ""-Strings:
  Beispiel: $id=1, $tab[0]=0, $tab[1]=2
  echo("$id/$tab[0]/$tab[$id]"); liefert nicht wie erwartet 1/0/2, sondern
  1/tab[0]/tab[id] (oder so aehnlich, hab's vergessen).
  Ursache: Arrays gehen schief. Anders der "->"-Operator:
  echo("$p->bla"); ist erlaubt.
- Die EMPTY-Abfrage zu Beginn der Methoden (vor allem bei Updatemethoden)
  habe ich ersetzt durch eine Abfrage $this->is_a (weil is_a erst ab PHP 4.2)
- Vor den letzten vier Updatemethoden habe ich eine kleine Anmerkung
  geschrieben, die einen Vorschlag zum Umgang mit Bug #71 macht.
============================================================================= */

/* =============================================================================
   Dinge, die zu beachten sind (extra fuer Sandro Auszuege aus dem ICQ-Log
   (leicht angepasst), recht unstrukturiert, mehr so als Themensammlung, was man
   im Hinterkopf behalten sollte):
----------------------------------------------------------------------------
du solltest nach funktionsaufrufen von dbaccess-methoden nicht
if ($this->mySql->failed())
pruefen, sondern
if ($this->failed())
analog die fehlermeldung $this->mySql->getLastError durch $this->getLastError
ersetzen.
----------------------------------------------------------------------------
$objPaper = new Paper(...)
if ($this->mySql->failed()) ...
muss (abgesehen davon, dass es (s.o.) $this->failed() heißen muesste) entfernt
werden, weil die konstruktoren gar keine fehlerbehandlung machen, insbesondere
auch keinen rueckgabewert haben
----------------------------------------------------------------------------
man darf fehlerabfragen wirklich nur durchfuehren, wenn die aufgerufene methode
auch fehlerbehandlung tut... (paper ist ja nicht mal von errorhandling
abgeleitet)
... sonst ist das verhalten undefiniert: ist zuletzt in der klasse ein fehler
aufgetreten, wuerde hier wieder derselbe auftreten (da errorhandling das
fehlerflag nicht loescht, bis eine operation erfolgreich war), sonst "zufaellig"
keiner
----------------------------------------------------------------------------
oben muss es natuerlich nicht immer $this->failed() heißen, sondern evtl. auch
$this->klasse->failed(), falls die pruefung sich auf eine methode der klasse
$klasse bezieht...
----------------------------------------------------------------------------

Weitere (neue) Infos:

// ACHTUNG!! FALLE:
// Bei for-Schleifen bei der Benutzung von count unbedingt zu pruefen, ob
// nicht etwa FALSE zurueckgegeben wurde => count(FALSE)=1 !!! Bei SQL-
// Statements ist dies nicht der Fall (weil wir das leere Array
// zurueckbekommen), aber bei Methodenaufrufen von DBAccess ist dies durchaus
// moeglich.
//
// Die aktuellen Vorkommen (2) davon habe ich gefixt.
// Ein Beispiel, wie dies elegant geschieht aus getForumsOfPerson:
// for ($i = 0; $i < count($objAllForums) && !empty($objAllForums); $i++)
----------------------------------------------------------------------------
Bei Methoden, die dank der neuen Fehlerbehandlung im Erfolgsfall irgendwas
zurueckgeben, z.B. einen int, und einen Fehler, wenn etwas schief geht, ist in
Skripten, die diese benutzen, keine empty-Pruefung mehr notwendig.
Vgl. addReviewReport und die Benutzung dessen in createNewReviewReport.
----------------------------------------------------------------------------
Bei Methoden wie addPrefersTopic habe ich den Rueckgabewert jetzt bool gemacht
(wobei dieser true ist, ausser im Fehlerfall, aber da gibt es ja ohnehin auto-
matisch false); int macht hier keinen Sinn, weil keine Auto-ID angelegt wird,
die man zurueckgeben koennte.
Bei den delete-Methoden entsprechend.
----------------------------------------------------------------------------
Noch ne Winzigkeit (peniboloestest): Im String, der bei der error-Methode den
Methodennamen enthaelt, bitte kein Leerzeichen am Ende. ;-) (ist gefixt)

============================================================================= */

require_once(INCPATH.'header.inc.php');

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

  /**
   * Prueft, ob das Objekt $obj eine Instanz der Klasse mit Namen $strClass
   * oder eines Derivats davon ist.
   *
   * @param object $obj Das Objekt
   * @param string $strClass Der Name der zu ueberpruefenden Klasse
   * @return true gdw. $obj eine Instanz von $strClass oder eines Derivats davon ist
   * @author Tom (15.01.05)
   * @access private
   */
  function is_a($obj, $strClass) {
    // Wegen Kompatibilitaet zu PHP < 4.2 urspruenglich:
    //$s = strtolower($strClass);
    //return (strtolower(get_class($obj)) == $s || is_subclass_of($obj, $s));
    return is_a($obj, $strClass);
  }

  // ---------------------------------------------------------------------------
  // Definition der Selektoren
  // ---------------------------------------------------------------------------

 /**
   * Prueft, ob die Email-Adresse in der Datenbank gespeichert wurde.
   *
   * @return bool true gdw. die Email in der Datenbank gefunden wurde
   * @access public
   * @author Daniel (20.12.04)
   */
  function checkEmail($strEmail) {
    $s = "SELECT  email".
        " FROM    Person".
        " WHERE   email = '$strEmail'";
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
   * @param string $strPassword Das zu ueberpruefende Passwort
   * @return bool true gdw. die Daten in der Person-Tabelle hinterlegt sind
   * @access public
   * @author Tom (15.12.04)
   */
  function checkLogin($strUserName, $strPassword) {
    $s = "SELECT  email, password".
        " FROM    Person".
        " WHERE   email = '$strUserName'".
        " AND     password = '$strPassword'";
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
    for ($i = 0; $i < count($data); $i++) {
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
   * @return ConferenceDetailed [] Gibt ein leeres Array zurueck, falls die Konferenz
   *                               nicht existiert.
   * @access public
   * @author Tom (08.01.05)
   */
  function getConferenceDetailed($intConferenceId) {
    $s = "SELECT  c.id, name, homepage, description, abstract_submission_deadline,".
        "         paper_submission_deadline, review_deadline, final_version_deadline,".
        "         notification, conference_start, conference_end, min_reviews_per_paper,".
        "         default_reviews_per_paper, min_number_of_papers, max_number_of_papers,".
        "         critical_variance, auto_activate_account, auto_open_paper_forum,".
        "         auto_add_reviewers, number_of_auto_add_reviewers".
        " FROM    Conference AS c".
        " INNER   JOIN ConferenceConfig AS cc".
        " ON      c.id = cc.id".
        " WHERE   c.id = '$intConferenceId'";
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
    $s = "SELECT  id, name, description, max_value, quality_rating".
        " FROM    Criterion".
        " WHERE   conference_id = '$intConferenceId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getCriterionsOfConference', $this->mySql->getLastError());
    }
    $objCriterions = array();
    for ($i = 0; $i < count($data); $i++) {
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
    $s = "SELECT  id, name".
        " FROM    Topic".
        " WHERE   conference_id = '$intConferenceId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getTopicsOfConference', $this->mySql->getLastError());
    }
    $objTopics = array();
    for ($i = 0; $i < count($data); $i++) {
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
    $s = "SELECT  id".
        " FROM    Person".
        " WHERE   email = '$strEmail'";
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
    $s = "SELECT  id, first_name, last_name, email, title".
        " FROM    Person".
        " WHERE   id = '$intPersonId'";
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
      $s = "SELECT  role_type".
          " FROM    Role".
          " WHERE   person_id = '".$data[0]['id']."'".
          " AND     conference_id = '$intConferenceId'";
      $role_data = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getPerson', $this->mySql->getLastError());
      }
      for ($i = 0; $i < count($role_data); $i++) {
        $objPerson->addRole($role_data[$i]['role_type']);
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
    $s = "SELECT  id, first_name, last_name, email, title".
        " FROM    Person".
        " WHERE   id = '$intPersonId'";
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
    $s = "SELECT  role_type".
        " FROM    Role".
        " WHERE   person_id = '".$data[0]['id']."'".
        " AND     conference_id = '$intConferenceId'";
    $role_data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPersonAlgorithmic', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($role_data); $i++) {
      $objPersonAlgorithmic->addRole($role_data[$i]['role_type']);
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
    $s = "SELECT  id, first_name, last_name, email, title, affiliation,".
        "         street, city, postal_code, state, country, phone_number, fax_number".
        " FROM    Person".
        " WHERE   id = '$intPersonId'";
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
      $s = "SELECT  role_type".
          " FROM    Role".
          " WHERE   person_id = '".$data[0]['id']."'".
          " AND     conference_id = '$intConferenceId'";
      $role_data = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getPersonDetailed', $this->mySql->getLastError());
      }
      for ($i = 0; $i < count($role_data); $i++) {
        $objPersonDetailed->addRole($role_data[$i]['role_type']);
      }
    }
    return $this->success($objPersonDetailed);
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
    $s = "SELECT  id, author_id, title, state".
        " FROM    Paper".
        " WHERE   id = '$intPaperId'";
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
    $strAuthor = $objAuthor->getName();
    $objPaper = (new PaperSimple($intPaperId, $data[0]['title'], $data[0]['author_id'],
                  $strAuthor, $data[0]['state'], $fltAvgRating,
                  $this->getTopicsOfPaper($intPaperId)));
    return $this->success($objPaper);
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten des Autors $intAuthorId..
   *
   * @param int $intAuthorId ID des Autors
   * @return PaperSimple [] Ein leeres Array, falls keine Papers des Autors
   *                        $intAuthorId gefunden wurden.
   * @access public
   * @author Tom (04.12.04, 12.12.04)
   */
  function getPapersOfAuthor($intAuthorId) {
    $objAuthor = $this->getPerson($intAuthorId);
    if ($this->failed()) {
      return $this->error('getPapersOfAuthor', $this->getLastError());
    }
    else if (empty($objAuthor)) {
      return $this->success(array());
    }
    $strAuthor = $objAuthor->getName();
    $s = "SELECT  id, author_id, title, state".
        " FROM    Paper".
        " WHERE   author_id = '$intAuthorId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPapersOfAuthor', $this->mySql->getLastError());
    }
    $objPapers = array();
    for ($i = 0; $i < count($data); $i++) {
      $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfAuthor', $this->getLastError());
      }
      $objPapers[] = (new PaperSimple($data[$i]['id'], $data[$i]['title'],
                       $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
                       $fltAvgRating, $this->getTopicsOfPaper($data[$i]['id'])));
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten des Reviewers $intReviewerId.
   *
   * @param int $intReviewerId ID des Reviewers
   * @return PaperSimple [] Ein leeres Array, falls keine Reviews des Reviewers
   *                        $intReviewerId gefunden wurden.
   * @access public
   * @author Tom (12.12.04)
   */
  function getPapersOfReviewer($intReviewerId) {
    $s = "SELECT  p.id AS id, author_id, title, state".
        " FROM    Paper AS p".
        " INNER   JOIN ReviewReport AS r".
        " ON      r.paper_id = p.id".
        " AND     r.reviewer_id = '$intReviewerId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPapersOfReviewer', $this->mySql->getLastError());
    }
    $objPapers = array();
    for ($i = 0; $i < count($data); $i++) {
      $objReviewer = $this->getPerson($intReviewerId);
      if ($this->mySql->failed()) {
         return $this->error('getPapersOfReviewer', $this->mySql->getLastError());
      }
      else if (empty($objReviewer)) {
        return $this->error('getPapersOfReviewer', 'Fatal error: Database inconsistency!',
                            "intReviewerId = $intReviewerId");
      }
      $strReviewer = $objReviewer->getName();
      $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      if ($this->failed()) {
        return $this->error('getPapersOfReviewer', $this->getLastError());
      }
      $objPapers[] = (new PaperSimple($data[$i]['id'], $data[$i]['title'],
                       $data[$i]['author_id'], $strReviewer, $data[$i]['state'],
                       $fltAvgRating, $this->getTopicsOfPaper($data[$i]['id'])));
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
    $s = "SELECT  author_id, title, state, abstract, mime_type, last_edited, filename".
        " FROM    Paper".
        " WHERE   id = '$intPaperId'";
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
    $strAuthor = $objAuthor->getName();
    $fltAvgRating = $this->getAverageRatingOfPaper($intPaperId);
    // Co-Autoren
    $s = "SELECT  person_id AS coauthor_id, name".
        " FROM    IsCoAuthorOf AS i".
        " LEFT    JOIN Person AS p".
        " ON      p.id = i.person_id".
        " WHERE   paper_id = '$intPaperId'".
        " ORDER   BY person_id DESC"; // ORDER BY: Co-Autoren im System im Array vorne!
    $cadata = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPaperDetailed', $this->mySql->getLastError());
    }
    $intCoAuthorIds = array();
    $strCoAuthors = array();
    for ($i = 0; $i < count($cadata); $i++) {
      $intCoAuthorIds[] = $cadata[$i]['coauthor_id'];
      $objCoAuthor = $this->getPerson($cadata[$i]['coauthor_id']);
      if ($this->failed()) {
        return $this->error('getPaperDetailed', $this->getLastError());
      }
      // Co-Autor nicht im System? => nimm Name aus Tabelle
      $strCoAuthors[] = empty($objCoAuthor) ?
                        $cadata[$i]['name'] :
                        $objCoAuthor->getName();
    }
    $objTopics = $this->getTopicsOfPaper($intPaperId);
    if ($this->failed()) {
      return $this->error('getPaperDetailed', $this->getLastError());
    }
    $objPaper = (new PaperDetailed($intPaperId, $data[0]['title'], $data[0]['author_id'],
                  $strAuthor, $data[0]['state'], $fltAvgRating, $intCoAuthorIds,
                  $strCoAuthors, $data[0]['abstract'], $data[0]['mime_type'],
                  $data[0]['last_edited'], $data[0]['filename'], $objTopics));
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
    $s = "SELECT  t.id AS id, t.name AS name".
        " FROM    Topic AS t".
        " INNER   JOIN IsAboutTopic AS a".
        " ON      a.topic_id = t.id".
        " WHERE   a.paper_id = '$intPaperId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getTopicsOfPaper', $this->mySql->getLastError());
    }
    $objTopics = array();
    for ($i = 0; $i < count($data); $i++) {
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
    $s = "SELECT  SUM(((r.grade-1)/(c.max_value-1))*(c.quality_rating/100)) AS total_rating".
        " FROM    ReviewReport AS rr".
        " INNER   JOIN Rating AS r".
        " ON      r.review_id = rr.id".
        " INNER   JOIN Criterion AS c".
        " ON      c.id = r.criterion_id".
        " WHERE   rr.paper_id = '$intPaperId'".
        " GROUP   BY rr.id";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getAverageRatingOfPaper', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $sum = 0;
    for ($i = 0; $i < count($data); $i++) {
      $sum += $data[$i]['total_rating'];
    }
    return $this->success($sum / count($data));
  }

  /**
   * Liefert die Gesamtbewertung eines Reviews (die Durchschnittsnote aller
   * Kriterien unter Beruecksichtigung der Gewichtungen).
   *
   * @param int $intReviewId ID des Reviews
   * @return int false, falls keine Ratings zu den Kriterien gemacht wurden.
   * @access private
   * @author Sandro, Tom (06.12.04, 12.12.04)
   */
  function getReviewRating($intReviewId) {
    $s = "SELECT  SUM(((r.grade-1)/(c.max_value-1))*(c.quality_rating/100)) AS total_rating".
        " FROM    Rating AS r".
        " INNER   JOIN Criterion AS c".
        " ON      c.id = r.criterion_id".
        " AND     r.review_id = '$intReviewId'";
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
   * Liefert ein Array von Review-Objekten des Reviewers $intReviewerId zurueck.
   *
   * @param int $intReviewerId ID des Reviewers
   * @return Review Ein leeres Array, falls kein Review des Reviewers existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewsOfReviewer($intReviewerId) {
    $s = "SELECT  id".
        " FROM    ReviewReport".
        " WHERE   reviewer_id = '$intReviewerId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewsOfReviewer', $this->mySql->getLastError());
    }
    $objReviews = array();
    for ($i = 0; $i < count($data); $i++) {
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
   * Liefert ein Array von Person-Objekten zurueck, die Reviewer des Papers $intPaperId sind.
   *
   * @param int $intPaperId ID des Papers
   * @return Person [] Ein leeres Array, falls das Paper keine Reviewer besitzt.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewersOfPaper($intPaperId) {
    $s = "SELECT  reviewer_id".
        " FROM    ReviewReport".
        " WHERE   paper_id = '$intPaperId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewersOfPaper', $this->mySql->getLastError());
    }
    $objReviewers = array();
    for ($i = 0; $i < count($data); $i++) {
      $objReviewer = $this->getPerson($data[$i]['reviewer_id']);
      if ($this->mySql->failed()) {
        return $this->error('getReviewersOfPaper', $this->mySql->getLastError());
      }
      else if(empty($objReviewer)) {
        return $this->error('getReviewsOfPaper', 'Fatal error: Database inconsistency!',
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
    $s = "SELECT  id".
        " FROM    ReviewReport".
        " WHERE   paper_id = '$intPaperId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewsOfPaper', $this->mySql->getLastError());
    }
    $objReviews = array();
    for ($i = 0; $i < count($data); $i++) {
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
   * Liefert ein Review-Objekt mit den Daten des Reviews $intReviewId zurueck.
   *
   * @param int $intReviewId ID des Reviews
   * @return Review false, falls das Review nicht existiert.
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReview($intReviewId) {
    $s = "SELECT  id, paper_id, reviewer_id".
        " FROM    ReviewReport".
        " WHERE   id = '$intReviewId'";
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
      return $this->error('getReview', $this->mySql->getLastError());
    }
    else if (empty($objAuthor)) {
      return $this->error('getReview', 'Fatal error: Database inconsistency!',
                          "intAuthorId = $objPaper->intAuthorId");
    }
    $objReview = (new Review($data[0]['id'], $data[0]['paper_id'], $paper_data[0]['title'],
                   $objAuthor->strEmail, $objAuthor->getName(), getReviewRating($intReviewId),
                   getAverageRatingOfPaper($paper_data[0]['id']), $objReviewer->strEmail,
                   $objReviewer->getName()));
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
    $s = "SELECT  id, paper_id, reviewer_id, summary, remarks, confidential".
        " FROM    ReviewReport".
        " WHERE   id = '$intReviewId'";
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
      return $this->error('getReviewDetailed', $this->mySql->getLastError());
    }
    else if (empty($objAuthor)) {
      return $this->error('getReviewDetailed', 'Fatal error: Database inconsistency!',
                          "intAuthorId = $objPaper->intAuthorId");
    }
    $s = "SELECT  r.grade, r.comment, c.id, c.name, c.description, c.max_value,".
        "         c.quality_rating".
        " FROM    Rating r".
        " INNER   JOIN Criterion c".
        " ON      c.id  = r.criterion_id".
        " WHERE   review_id = '".$data[0]['id']."'";
    $rating_data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getReviewDetailed', $this->mySql->getLastError());
    }
    $intRatings = array();
    $strComments = array();
    $objCriterions = array();
    for ($i = 0; $i < count($rating_data); $i++) {
      $intRatings[] = $rating_data[$i]['grade'];
      $strComments[] = $rating_data[$i]['comment'];
      $objCriterions[] = (new Criterion($rating_data[$i]['id'], $rating_data[$i]['name'],
                            $rating_data[$i]['description'], $rating_data[$i]['max_value'],
                            $rating_data[$i]['quality_rating'] / 100.0));
    }
    $objReview = (new ReviewDetailed($data[0]['id'], $data[0]['paper_id'],
                   $paper_data[0]['title'], $objAuthor->strEmail, $objAuthor->getName(),
                   getReviewRating($intReviewId), getAverageRatingOfPaper($paper_data[0]['id']),
                   $objReviewer->strEmail, $objReviewer->getName(),
                   $data[0]['summary'], $data[0]['remarks'], $data[0]['confidential'],
                   $intRatings, $strComments, $objCriterions));
    return $this->success($objReview);
  }

  /**
   * Prueft, ob die Person $intPersonId Zugang zum Forum $intForumId hat.
   *
   * @param int $intPersonId Die zu pruefende Person.
   * @param int $intForumId Das zu pruefende Forum.
   * @return bool Gibt true zurueck gdw. die Person Zugriff auf das Forum hat
   * @access public
   * @author Sandro (12.01.05)
   * @todo (Sandros Job) Muss noch implementiert werden!
   */
  function checkAccessToForum($intPersonId, $intForumId) {
    return false;
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
    $s = "SELECT  id, sender_id, send_time, subject, text".
        " FROM    Message".
        " WHERE   reply_to = '$intMessageId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getNextMessages', $this->mySql->getLastError());
    }
    $messages = array();
    for ($i = 0; $i < count($data); $i++) {
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
    $s = "SELECT  id, sender_id, send_time, subject, text".
        " FROM    Message".
        " WHERE   forum_id = '$intForumId'".
        " AND     reply_to IS NULL";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getThreadsOfForum', $this->mySql->getLastError());
    }
    $objThreads = array();
    for ($i = 0; $i < count($data); $i++) {
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
   * Liefert ein Array von Forum-Objekten der Konferenz $intConferenceId zurueck.
   *
   * @param int $intConferenceId Die ID der Konferenz, deren Foren ermittelt werden sollen.
   * @return Forum [] Ein leeres Array, falls kein Forum in der Konferenz existiert.
   * @access public
   * @author Sandro (14.12.04)
   * @todo Einfuegen der Konstante fuer den Artikelforen-Typ!
   */
  function getAllForums($intConferenceId) {
    $s = "SELECT  id, title".
        " FROM    Forum".
        " WHERE   conference_id = '$intConferenceId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getAllForums', $this->mySql->getLastError());
    }
    $objForums = array();
    for ($i = 0; $i < count($data); $i++) {
      $objForums[] = (new Forum($data[$i]['id'], $data[$i]['title'], $data[$i]['forum_type'],
                        ($data[$i]['forum_type'] == 3) ? $data[$i]['paper_id'] : false));
      // [TODO] statt '3' die Konstante fuer den Artikelforen-Typ!
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
    $s = "SELECT  id, title, forum_type".
        " FROM    Forum".
        " WHERE   paperId = '$intPaperId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getForumOfPaper', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $forum = (new Forum($data[0]['id'], $data[0]['title'], $data[0]['forum_type'], $intPaperId));
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
   * @todo Anm. v. Tom: Auskommentierung der failed-Abfrage entfernen, sobald
   *       checkAccessToForum die Fehlerbehandlung integriert hat
   */
  function getForumsOfPerson($intPersonId, $intConferenceId) {
    $objForums = array();
    $objPerson = getPerson($intPersonId);
    if ($this->failed()) {
      return $this->error('getForumsOfPerson', $this->getLastError());
    }
    else if (empty($objPerson)) {
      return $this->success($objForums);
    }
    $objAllForums = getAllForums();
    if ($this->failed()) {
      return $this->error('getForumsOfPerson', $this->getLastError());
    }
    else if (empty($objAllForums)) {
      return $this->success($objForums);
    }
    for ($i = 0; $i < count($objAllForums) && !empty($objAllForums); $i++) {
      $bln = $this->checkAccessToForum($objPerson, $objAllForums[$i]);
      /*if ($this->failed()) {
        return $this->error('getForumsOfPerson', $this->getLastError());
      }*/
      if ($bln) {
        $objForums[] = $objAllForums[$i];
      }
    }
    return $this->success($objForums);
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
    $s = "SELECT  id, title".
        " FROM    Forum".
        " WHERE   id = '$intForumId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getForumDetailed', $this->mySql->getLastError());
    }
    else if (empty($data)) {
      return $this->success(false);
    }
    $forum = (new ForumDetailed($data[0]['id'], $data[0]['title'], 0, false,
                                $this->getThreadsOfForum($intForumId)));
    return $this->success($forum);
  }

  /**
   * Liefert ein Array der bevorzugten Topics der Person mit ID $intPersonId
   * bei der Konferenz $intConferenceId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId Konferenz-ID
   * @return Topic [] Ein leeres Array, falls die Person oder die Konferenz nicht existiert.
   * @access private
   * @author Tom (18.01.05)
   */
  function getPreferredTopics($intPersonId, $intConferenceId) {
    $objTopics = array();
    $s = "SELECT  pt.topic_id AS topic_id, t.name AS name".
        " FROM    PrefersTopic pt".
        " INNER   JOIN Topic t".
        " ON      t.id = pt.topic_id".
        " AND     t.conference_id = '$intConferenceId'".
        " WHERE   pt.person_id = '$intPersonId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPreferredTopics', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data); $i++) {
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
   * @return Topic [] Ein leeres Array, falls die Person oder die Konferenz nicht existiert.
   * @access private
   * @author Tom (18.01.05)
   */
  function getPreferredPapers($intPersonId, $intConferenceId) {
    $objPapers = array();
    $s = "SELECT  pp.paper_id AS paper_id".
        " FROM    PrefersPaper pp".
        " INNER   JOIN Paper p".
        " ON      p.id = pp.paper_id".
        " AND     p.conference_id = '$intConferenceId'".
        " WHERE   pp.person_id = '$intPersonId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getPreferredPapers', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data); $i++) {
      $objPapers[] = $this->getPaperSimple($data[$i]['paper_id']);
      if ($this->failed()) {
        return $this->error('getPreferredPapers', $this->getLastError());
      }
    }
    return $this->success($objPapers);
  }

  /**
   * Liefert ein Array der durch die Person $intPersonId zum Reviw abgelehnten Paper
   * bei der Konferenz $intConferenceId.
   *
   * @param int $intPersonId ID der Person
   * @param int $intConferenceId Konferenz-ID
   * @return Topic [] Ein leeres Array, falls die Person oder die Konferenz nicht existiert.
   * @access private
   * @author Tom (18.01.05)
   */
  function getDeniedPapers($intPersonId, $intConferenceId) {
    $objPapers = array();
    $s = "SELECT  dp.paper_id AS paper_id".
        " FROM    DeniesPaper dp".
        " INNER   JOIN Paper p".
        " ON      p.id = dp.paper_id".
        " AND     p.conference_id = '$intConferenceId'".
        " WHERE   dp.person_id = '$intPersonId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDeniedPapers', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data); $i++) {
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
   * @return Topic [] Ein leeres Array, falls die Person oder die Konferenz nicht existiert.
   * @access private
   * @author Tom (18.01.05)
   */
  function getExcludedPapers($intPersonId, $intConferenceId) {
    $objPapers = array();
    $s = "SELECT  ep.paper_id AS paper_id".
        " FROM    ExcludesPaper ep".
        " INNER   JOIN Paper p".
        " ON      p.id = ep.paper_id".
        " AND     p.conference_id = '$intConferenceId'".
        " WHERE   ep.person_id = '$intPersonId'";
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getExcludedPapers', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($data); $i++) {
      $objPapers[] = $this->getPaperSimple($data[$i]['paper_id']);
      if ($this->failed()) {
        return $this->error('getExcludedPapers', $this->getLastError());
      }
    }
    return $this->success($objPapers);
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
   * Die Kriterien der Konferenz werden ebenfalls aktualisiert (jedoch nicht das
   * Loeschen oder Hinzufuegen eines Kriteriums).
   *
   * @param ConferenceDetailed $objConferenceDetailed Konferenz-Objekt
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Tom (15.01.05)
   */
  function updateConference($objConferenceDetailed) {
    if (!($this->is_a($objConferenceDetailed, 'ConferenceDetailed'))) {
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
    $this->updateCriterions($objConferenceDetailed);
    if ($this->failed()) {
      return $this->error('updateConference', $this->getLastError());
    }
    $this->updateTopics($objConferenceDetailed);
    if ($this->failed()) {
      return $this->error('updateConference', $this->getLastError());
    }
    return $this->success();
  }

  /**
   * Aktualisiert den Datensatz der Person $objPersonDetailed in der Datenbank.
   * Die Rollen werden ebenfalls vollstaendig in der Datenbank aktualisiert.
   *
   * @param PersonDetailed $objPerson Person, die in der Datenbank aktualisiert werden soll
   * @param int $intConferenceId ID der Konferenz, zu der die Rollen der Person aktualisiert
   *                             werden sollen. Optional: Ist $intConfereceId nicht
   *                             angegeben, werden keine Rollen fuer die Person aktualisiert.
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (10.01.05)
   */
  function updatePerson($objPersonDetailed, $intConferenceId=false) {
    if (!($this->is_a($objPersonDetailed, 'PersonDetailed'))) {
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
    if (!empty($intConferenceId)) {
      $this->updateRoles($objPersonDetailed, $intConferenceId);
      if ($this->failed()) {
        return $this->error('updatePerson', $this->getLastError());
      }
    }
    return $this->success(true);
  }

  /**
   * Aktualisiert die Rollen der Person $objPerson bzgl. der Konferenz
   * $intConferenceId in der Datenbank.
   *
   * @param Person $objPerson    Person-Objekt mit aktuellen Rollen
   * @param int $intConferenceId Konferenz-ID
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access private
   * @author Tom (11.01.05)
   */
  function updateRoles($objPerson, $intConferenceId) {
    global $intRoles;
    if (!($this->is_a($objPerson, 'Person'))) {
      return $this->success(false);
    }
    $intId = $objPerson->intId;
    // Rollen loeschen...
    $s = sprintf("DELETE   FROM Role".
                 " WHERE   person_id = '%d'".
                 " AND     conference_id = '%d'",
                 s2db($intId), s2db($intConferenceId));
    $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('updateRoles', $this->mySql->getLastError());
    }
    if (empty($objPerson->intRoles)) {
      return $this->success();
    }
    // Rollen einfuegen...
    for ($i = 0; $i < count($intRoles); $i++) {
      if ($objPerson->hasRole($intRoles[$i])) {
        $s = sprintf("INSERT   INTO Role (conference_id, person_id, role_type)".
                     " VALUES  ('%d', '%d', '%d')",
                     s2db($intConferenceId), s2db($intId), s2db($intRoles[$i]));
        $this->mySql->insert($s);
        if ($this->mySql->failed()) {
          return $this->error('updateRoles', $this->mySql->getLastError());
        }
      }
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
    if (!($this->is_a($objPaperDetailed, 'PaperDetailed'))) {
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
    for ($i = 0; $i < count($objPaperDetailed->intCoAuthorIds); $i++) {
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
    if (!($this->is_a($objPaperDetailed, 'PaperDetailed'))) {
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
    for ($i = 0; $i < count($objPaperDetailed->intCoAuthorIds); $i++) {
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
   * Sorgt nicht (!) dafuer, die aktuelle Dateiversion hochzuladen.
   *
   * @param PaperDetailed $objPaper Artikel, der in der Datenbank aktualisiert werden soll
   * @return bool true gdw. die Aktualisierung korrekt durchgefuehrt werden konnte
   * @access public
   * @author Sandro (10.01.05)
   */
  function updatePaper($objPaperDetailed) {
    if (!($this->is_a($objPaperDetailed, 'PaperDetailed'))) {
      return $this->success(false);
    }
    $s = sprintf("UPDATE   Paper".
                 " SET     title = '%s', author_id = '%d', abstract = '%s', mime_type = '%s',".
                 "         last_edited = '%s', filename = '%s', state = '%d'".
                 " WHERE   id = '%d'",
                 s2db($objPaperDetailed->strTitle), s2db($objPaperDetailed->intAuthorId),
                 s2db($objPaperDetailed->strAbstract), s2db($objPaperDetailed->strMimeType),
                 s2db($objPaperDetailed->strLastEdit), s2db($objPaperDetailed->strFilePath),
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
   * @todo (Sandros Job)
   */
  function updateReviewReport($objReviewDetailed) {
    if (!($this->is_a($objReviewDetailed, 'ReviewDetailed'))) {
      return $this->success(false);
    }
    return $this->success();
  }
  
  /**
   * @todo (Sandros Job)
   * @access private
   */
  function updateRating($objReviewDetailed) {
    if (!($this->is_a($objReviewDetailed, 'ReviewDetailed'))) {
      return $this->success(false);
    }
    return $this->success();
  }

  /**
   * @todo (Sandros Job)
   */
  function updateForum($objForumDetailed) {
    if (!($this->is_a($objForumDetailed, 'ForumDetailed'))) {
      return $this->success(false);
    }
    return $this->success();
  }

  /**
   * @todo
   */
  function updateMessage($objMessage) {
    if (!($this->is_a($objMessage, 'Message'))) {
      return $this->success(false);
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
    if (!($this->is_a($objConferenceDetailed, 'ConferenceDetailed'))) {
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
    if (!($this->is_a($objConferenceDetailed, 'ConferenceDetailed'))) {
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
    if (!($this->is_a($objPaperSimple, 'PaperSimple'))) {
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
    for ($i = 0; $i < count($objPaperSimple->objTopics); $i++) {
      $objTopics = $objPaperSimple->objTopics[$i];
      if (!empty($objTopics)) {
        $s = sprintf("INSERT   INTO (IsAboutTopic (paper_id, topic_id)".
                     " VALUES  ('%d', '%d')",
                     s2db($intId), s2db($objTopics->intId));
        echo("$s<br>");
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
    if (!($this->is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
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
    for ($i = 0; $i < count($objTopics); $i++) {
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
    for ($i = 0; $i < count($objTopics); $i++) {
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
    if (!($this->is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
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
    for ($i = 0; $i < count($objPapers); $i++) {
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
    for ($i = 0; $i < count($objPapers); $i++) {
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
    if (!($this->is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
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
    for ($i = 0; $i < count($objPapers); $i++) {
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
    for ($i = 0; $i < count($objPapers); $i++) {
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
    if (!($this->is_a($objPersonAlgorithmic, 'PersonAlgorithmic'))) {
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
    for ($i = 0; $i < count($objPapers); $i++) {
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
    for ($i = 0; $i < count($objPapers); $i++) {
      if (!empty($objPapers[$i])) {
        $this->addExcludesPaper($intPersonId, $objPapers[$i]->intId);
        if ($this->failed()) {
          return $this->error('updateExcludedPapers', $this->getLastError());
        }
      }
    }
    return $this->success();
  }

  // ---------------------------------------------------------------------------
  // Definition der Insert-Funktionen
  // ---------------------------------------------------------------------------

  /**
   * Legt eine neue Konferenz an.
   *
   * @param string $strAbstractDeadline Deadline fuer die Einsendung der Abstracts
   * @param string $strPaperDeadline    Deadline fuer die Einsendung der Paper zum Review
   * @param string $strReviewDeadline   Deadline fuer die Reviews
   * @param string $strFinalDeadline    Deadline fuer die Einsendung der finale Version der Paper (?)
   *
   * @access public
   * @author Daniel (31.12.04), ueberarbeitet von Tom (13.01.05)
   */
  function addConference($strName, $strHomepage, $strDescription, $strAbstractDeadline,
                         $strPaperDeadline, $strReviewDeadline, $strFinalDeadline,
                         $strNotification, $strConferenceStart, $strConferenceEnd) {
    $s = "INSERT  INTO Conference (name, homepage, description, abstract_submission_deadline,".
        "                          paper_submission_deadline, review_deadline,".
        "                          final_version_deadline, notification, conference_start,".
        "                          conference_end)".
        "         VALUES ('$strName', '$strHomepage', '$strDescription', '$strAbstractDeadline',".
        "                 '$strPaperDeadline', '$strReviewDeadline', '$strFinalDeadline',".
        "                 '$strNotification', '$strConferenceStart', '$strConferenceEnd')";    
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addConference', $this->mySql->getLastError());
    }
    $s = "INSERT  INTO ConferenceConfig (id)".
        "         VALUES ('$intId')";
    $this->mySql->insert($s);
    if ($this->mySql->failed()) { // Undo: Eingefuegten Satz wieder loeschen.
      $strError = $this->mySql->getLastError();
      $s = "DELETE  FROM Conference".
          " WHERE   id = '$intId'";
      if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
        return $this->error('addConference', 'Fatal error: Database inconsistency!',
                            $this->mySql->getLastError()." / $strError");
      }
      return $this->error('addConference', $this->mySql->getLastError());
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
    $s = "INSERT  INTO Person (first_name, last_name, title, affiliation, email,".
        "                      street, postal_code, city, state, country,".
        "                      phone_number, fax_number, password)".
        "         VALUES ('$strFirstname', '$strLastname', '$strTitle',".
        "                 '$strAffiliation', '$strEmail', '$strStreet',".
        "                 '$strPostalCode', '$strCity', '$strState',".
        "                 '$strcountry', '$strPhone', '$strFax',".
        "                 '".sha1($strPassword)."')";
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
  function addRole($intPersonId, $intRoleType, $intConferenceId) {
    $s = "INSERT  INTO Role (conference_id, person_id, role_type)".
        "         VALUES ('$intConferenceId', '$intPersonId', '$intRoleType')";
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
    $s = "INSERT  INTO IsCoAuthorOf (person_id, paper_id)".
        "         VALUES ('$intCoAuthorId', '$intPaperId')";
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
    $s = "INSERT  INTO IsCoAuthorOf (paper_id, name)".
        "         VALUES ('$intPaperId', '$strName')";
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
   * @param str $strFilePath     Dateipfad und -name
   * @param str $strMimeType     MIME-Typ
   * @param str $strCoAuthors[]  Namen der Co-Autoren
   * @return int ID des erzeugten Papers
   *
   * @access public
   * @author Tom (26.12.04)
   */
  function addPaper($intConferenceId, $intAuthorId, $strTitle, $strAbstract,
                    $strFilePath, $strMimeType, $strCoAuthors) {
    $s = "INSERT  INTO Paper (conference_id, author_id, title, abstract, filename,".
        "                     mime_type, state)".
        "         VALUES ('$intConferenceId', '$intAuthorId', '$strTitle',".
        "                 '$strAbstract', '$strFilePath', '$strMimeType', 0)";
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addPaper', $this->mySql->getLastError());
    }
    for ($i = 0; $i < count($strCoAuthors) && !empty($strCoAuthors); $i++) {
      $this->addCoAuthorName($intId, $strCoAuthors[$i]);
      if ($this->failed()) { // Undo: Eingefuegten Satz wieder loeschen.
        $s = "DELETE  FROM Paper".
            " WHERE   id = '$intId'";
        $this->mySql->delete($s);
        if ($this->mySql->failed()) { // Auch dabei ein Fehler? => fatal!
          return $this->error('addPaper', 'Fatal error: Database inconsistency!',
                              $this->mySql->getLastError().' / '.$this->getLastError());
        }
        return $this->error('addPaper', $this->getLastError());
      }
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle ReviewReport ein.
   *
   * @param int $intPaperId          ID des Papers, das bewertet wird
   * @param int $intReviewerId       ID des Reviewers
   * @param string $strSummary       Zusammenfassender Text fuer die Bewertung (inital: '')
   * @param string $strRemarks       Anmerkungen fuer den Autoren (inital: '')
   * @param string $strConfidential  Vertrauliche Anmerkungen fuer das Komitee (inital '')
   * @return int ID des erzeugten Review-Reports
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addReviewReport($intPaperId, $intReviewerId, $strSummary = '',
                           $strRemarks = '', $strConfidential = '') {
    $s = "INSERT  INTO ReviewReport (paper_id, reviewer_id, summary, remarks, confidential)".
        "         VALUES ('$intPaperId', '$intReviewerId',".
        "                 '$strSummary', '$strRemarks', '$strConfidential')";
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
   * @param int $intGrade        Note, Auspraegung der Bewertung (inital: 0)
   * @param string $strComment   Anmerkung zur Bewertung in dem Kriterium (inital: '')
   * @return int ID des erzeugten Ratings
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addRating($intReviewId, $intCriterionId, $intGrade = 0, $strComment = '') {
    $s = "INSERT  INTO Rating (review_id, criterion_id, grade, comment)".
        "         VALUES ('$intReviewId', '$intCriterionId', '$intGrade', '$strComment')";
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addCriterion', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Erstellt einen neuen Review-Report-Datensatz in der Datenbank, sowie die
   * mit diesem Review-Report assoziierten Ratings (Bewertungen in den einzelnen
   * Kriterien). Initial sind die Bewertungen 0 und die Kommentartexte leer.
   *
   * @todo [TODO] Bleibt evtl. nicht an dieser Stelle stehen, sondern wandert in ein anderes Skript!
   *        Anm. v. Tom: Dann ALLE Vorkommen von $this durch $myDBAccess (oder so)
   *                     ersetzen, sonst drohen Probleme wegen der Fehlerbehandlung!
   */

   function createNewReviewReport($intPaperId, $intReviewerId, $intConferenceId) {
     //$intConferenceId = $_SESSION['confid'];
     $intReviewId = $this->addReviewReport($intPaperId, $intReviewerId);
     if ($this->failed()) {
        return $this->error('createNewReviewReport', $this->getLastError());
     }
     $objCriterions = $this->getCriterionsOfConference($intConferenceId);
     if ($this->failed()) {
        return $this->error('createNewReviewReport', $this->getLastError());
     }
     for ($i = 0; $i < count($objCriterions) && !empty($objCriterions); $i++) {
        $this->addRating($intReviewId, $objCriterions[$i]->intId, 0, '');
        if ($this->failed()) {
          return $this->error('createNewReviewReport', $this->getLastError());
        }
     }
     return $this->success($intReviewId);
   }

  /**
   * Fuegt einen Datensatz in die Tabelle Forum ein.
   *
   * @param int $intConferenceId  ID der Konferenz, fuer die das Forum angelegt wird
   * @param string $strTitle      Bezeichnung des Forums
   * @param int $intForumType     Art des Forums (1: globales, 2:Komitee-, 3:Artikelforum)
   * @param int $intPaperId       ID des assoziierten Artikels bei Artikelforen (sonst: 0)
   * @return int ID des erzeugten Forums
   *
   * @access public
   * @author Sandro (18.12.04)
   * @todo Statt '3' die Konstante fuer den Artikelforen-Typ einfuegen!
   */
  function addForum($intConferenceId, $strTitle, $intForumType, $intPaperId = 0) {
    if ($intForumType <> 3) {  // [TODO] statt '3' die Konstante fuer den Artikelforen-Typ!
      $intPaperId = 0;
    }
    $s = "INSERT  INTO Forum (conference_id, title, forum_type, paper_id)".
        "         VALUES ('$intConferenceId', '$strTitle', '$intForumType', '$intPaperId')";
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addForum', $this->mySql->getLastError());
    }
    return $this->success($intId);
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Message ein.
   *
   * @param string $strSubject   Betreff der Message
   * @param string $strText      Inhalt der Message
   * @param string $intSenderId  ID des Erstellers der Message
   * @param int $intForumId      ID des Forums, in das die Message eingefuegt wird
   * @param int $intReplyTo      ID der Nachricht, auf welche die Message antwortet
   *                             (falls die Message einen neuen Thread eroeffnet: 0)
   *                             Anm. v. Tom: Ist das mit der 0 so sicher? (ich seh es irgendwie nicht)
   * @return int ID der erzeugten Message
   *
   * @access public
   * @author Sandro (18.12.04)
   */
  function addMessage($strSubject, $strText, $intSenderId, $intForumId, $strReplyTo = 0) {
    $strSendTime = date("d.m.Y H:i");
    $s = "INSERT  INTO Message (subject, text, sender_id, forum_id, reply_to, send_time)".
        "         VALUES ('$strSubject', '$strText', '$intSenderId', '$intForumId',".
        "                 '$intReplyTo', '$strSendTime')";
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
    $intQualityRating = round($fltWeight * 100);
    $s = "INSERT  INTO Criterion (conference_id, name, description, max_value, quality_rating)".
        "         VALUES ('$intConferenceId', '$strName', '$strDescription',".
        "                 '$intMaxValue', '$intQualityRating')";
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
    $s = "INSERT  INTO Topic (conference_id, name)".
        "         VALUES ('$intConferenceId', '$strName')";
    $intId = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addDeniesPaper', $this->mySql->getLastError());
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
    $s = "INSERT  INTO IsAboutTopic (paper_id, topic_id)".
        "         VALUES ('$intPaperId', '$intTopicId')";
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
    $s = "INSERT  INTO PrefersTopic (person_id, topic_id)".
        "         VALUES ('$intPersonId', '$intTopicId')";
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
    $s = "INSERT  INTO PrefersPaper (person_id, paper_id)".
        "         VALUES ('$intPersonId', '$intPaperId')";
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
    $s = "INSERT  INTO DeniesPaper (person_id, paper_id)".
        "         VALUES ('$intPersonId', '$intPaperId')";
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
    $s = "INSERT  INTO ExcludesPaper (person_id, paper_id)".
        "         VALUES ('$intPersonId', '$intPaperId')";
    $result = $this->mySql->insert($s);
    if ($this->mySql->failed()) {
      return $this->error('addExcludesPaper', $this->mySql->getLastError());
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
    $s = "DELETE  FROM Conference".
        " WHERE   id = '$intConferenceId'";
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
    $s = "UPDATE  Person".
        " SET     password = ''".
        " WHERE   id = '$intPersonId'";
    $result = $this->mySql->update($s);
    if ($this->mySql->failed()) {
      return $this->error('deactivateAccount', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   * Loescht die Person mit der ID $intPersonId.
   *
   * @param int $intPersonId Personen-ID
   * @return bool true gdw. erfolgreich
   * @access public
   * @author Tom (26.12.04)
   */
  function deletePerson($intPersonId) {
    $s = "DELETE  FROM Person".
        " WHERE   id = '$intPersonId'";
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
    $s = "DELETE  FROM Paper".
        " WHERE   id = '$intPaperId'";
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
    $s = "DELETE  FROM Role".
        " WHERE   conference_id = '$intConferenceId'".
        " AND     person_id = '$intPersonId'".
        " AND     role_type = '$intRoleType'";
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
    $s = "DELETE  FROM IsCoAuthorOf".
        " WHERE   person_id = '$intCoAuthorId'".
        " AND     paper_id = '$intPaperId'";
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
    $s = "DELETE  FROM IsCoAuthorOf".
        " WHERE   paper_id = '$intPaperId'".
        " AND     name = '$strName'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteCoAuthorName', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteReviewReport($intReviewId) {
    $s = "DELETE  FROM Reviewreport".
        " WHERE   id = '$intReviewId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteReviewReport', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteRating($intReviewId, $intCriterionId) {
    $s = "DELETE  FROM Rating".
        " WHERE   review_id = '$intReviewId'".
        " AND     criterion_id = '$intCriterionId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteRating', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteForum($intForumId) {
    $s = "DELETE  FROM Forum".
        " WHERE   id = '$intForumId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteForum', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteMessage($intMessageId) {
    $s = "DELETE  FROM Message".
        " WHERE   id = '$intMessageId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteMessage', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteCriterion($intCriterionId) {
    $s = "DELETE  FROM Criterion".
        " WHERE   id = '$intCriterionId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteCriterion', $this->mySql->getLastError());
    }
    return $this->success();

  }

  /**
   */
  function deleteTopic($intTopicId) {
    $s = "DELETE  FROM Topic".
        " WHERE   id = '$intTopicId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteIsAboutTopic($intPaperId, $intTopicId) {
    $s = "DELETE  FROM IsAboutTopic".
        " WHERE   paper_id = '$intPaperId'".
        " AND     topic_id = '$intTopicId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteIsAboutTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deletePrefersTopic($intPersonId, $intTopicId) {
    $s = "DELETE  FROM PrefersTopic".
        " WHERE   person_id = '$intPersonId'".
        " AND     topic_id = '$intTopicId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deletePrefersTopic', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deletePrefersPaper($intPersonId, $intPaperId) {
    $s = "DELETE  FROM PrefersPaper".
        " WHERE   person_id = '$intPersonId'".
        " AND     paper_id = '$intPaperId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deletePrefersPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteDeniesPaper($intPersonId, $intPaperId) {
    $s = "DELETE  FROM DeniesPaper".
        " WHERE   person_id = '$intPersonId'".
        " AND     paper_id = '$intPaperId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteDeniesPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  /**
   */
  function deleteExcludesPaper($intPersonId, $intPaperId) {
    $s = "DELETE  FROM ExcludesPaper".
        " WHERE   person_id = '$intPersonId'".
        " AND     paper_id = '$intPaperId'";
    $result = $this->mySql->delete($s);
    if ($this->mySql->failed()) {
      return $this->error('deleteExcludesPaper', $this->mySql->getLastError());
    }
    return $this->success();
  }

  // end of class DBAccess
}

?>