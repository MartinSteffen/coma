<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

// OFFENE PUNKTE (nur diese Klasse betreffend, daher nicht in Spec):
// -----------------------------------------------------------------
// - Wie werden Booleans behandelt? (vgl. getConferenceConfig, blnAuto...)
//   Jeweils zwischen 0/1 (DB) und false/true (Klasse) switchen?
//   Sonst Gefahr: Abfrage ($bln = false) liefert wahr, wenn $bln gesetzt ist
//   (also insbesondere auch bei $bln = 0, was je false bedeuten sollte)
//   Alternative (in DB): NULL = false, 1 = true


// by Jan: verbesserter Include
if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}
require_once(INCPATH.'class.mysql.inc.php');

require_once(INCPATH.'class.conference.inc.php');
require_once(INCPATH.'class.conferencedetailed.inc.php');
require_once(INCPATH.'class.criterion.inc.php');
require_once(INCPATH.'class.forum.inc.php');
require_once(INCPATH.'class.forumdetailed.inc.php');
require_once(INCPATH.'class.message.inc.php');
require_once(INCPATH.'class.paper.inc.php');
require_once(INCPATH.'class.paperdetailed.inc.php');
require_once(INCPATH.'class.papersimple.inc.php');
require_once(INCPATH.'class.person.inc.php');
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
class DBAccess {
  /**#@+
   * @access private
   */
  /**
   * @var MySql
   */
  var $mySql;
  /**
   * @var string
   */
  var $strError = '';
  /**#@-*/

  /**
   * Konstruktor: Erzeugt eine Verbindung mit der Datenbank.
   *
   * @param MySql $mySql ein MySql-Objekt
   * @return bool <b>true</b> bei Erfolg, <b>false</b> bei Fehler
   * @see error()
   * @see getLastError()
   */
  function DBAccess(&$mySql) {
    $this->mySql = &$mySql;
    return true;
  }

  /**
   * Legt die Fehlerbeschreibung fest.
   *
   * @param string $strError optionale Fehlerbeschreibung
   * @return bool immer <b>false</b>
   * @see getLastError()
   * @access protected
   */
  function error($strError='') {
    $this->strError = "[DBAccess: $strError ]";
    return false;
  }

  /**
   * Liefert die letzte Fehlerbeschreibung zurueck.
   *
   * Die Funktion <b>getLastError()</b> gibt die letzte mit error
   * gesicherte Fehlermeldung zurueck und loescht diese aus dem Speicher.
   *
   * @return string die letzte Fehlermeldung
   * @see error()
   * @access public
   */
  function getLastError() {
    $strError = $this->strError;
    $this->strError = '';
    return $strError;
  }

  // ---------------------------------------------------------------------------
  // Definition der Selektoren
  // ---------------------------------------------------------------------------

  /**
   * Liefert einen Array mit allen Konferenzen zurück.
   *
   * @return Conferences[] bzw. <b>false</b>, falls keine Konferenz angelegt ist.
   * @access public
   * @author Daniel (29.12.04)
   */

  function  getAllConferences(){
    $s = 'SELECT  id, name, homepage, description, conference_start,  conference_end '.
        ' FROM    Conference';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objConferenceNames= array();
      for ($i = 0; $i < count($data); $i++) {
        $objConferenceNames[$i] = (new Conference($data[$i]['id'], $data[$i]['name'],
                                                $data[$i]['homepage'], $data[$i]['description'], 
                                                $data[$i]['conference_start'],
                                                $data[$i]['conference_end']));
      }
      return $objConferenceNames;
    }
    return $this->error('getAllConferences'.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array mit Rollen-Namen zurück.
   *
   * @param int $intId ID der Person und  $IntConfId ID der Konferenz
   * @return Array mit Rollen_Namen (siehe Spec von PHP1)
   * @access public
   * @author Daniel (29.12.04)
   */
  function  getRoles($intId, $intConfId){
    $s = 'SELECT  role_type '.
        ' FROM    Role'.
        ' WHERE   conference_id = \' '.$intConfId.' \' '.
        ' AND     person_id= \''.$intId.'\'';
   $data = $this->mySql->select($s);
   $strRoles= array();
   if (!empty($data)) {
      for ($i = 0; $i < count($data); $i++) {
        // Rollen die nach der PHP1 Spec nicht verwendet werden dürfen auch in der Datenbank nicht
	// vorkommen
        if (($data[$i]['role_type'] >4)||($data[$i]['role_type']==1)){ 
          $strRoles[$i] = 'undef';
          echo 'Rolle: '.$data[$i]['role_type'].' ist in der Funktion getRoles nicht bekannt';
        }
        else {
        if ($data[$i]['role_type']==2){$strRoles[$i] = 'Chair';}
        if ($data[$i]['role_type']==3){$strRoles[$i] = 'Reviewer';}
        if ($data[$i]['role_type']==4){$strRoles[$i] = 'Autor';}
        if ($data[$i]['role_type']==0){$strRoles[$i] = 'Teilnehmer';}
        }
      }
   }
   return $strRoles;
 
  }
 /**
   * Prueft, ob die Email-Adresse in der Datenbank gespeichert wurde.
   *
   * @return bool true gdw. die Email in der Datenbank gefunden wurde
   * @access public
   * @author Daniel (20.12.04)
   */
  function checkEmail($strEmail) {
    $s = 'SELECT  email '.
         ' FROM    Person'.
         ' WHERE   email = \''.$strEmail.'\'';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      return true;
    }
    return false;
  }


  /**
   * Prueft, ob die globalen User-Daten gueltig sind.
   * Falls die Daten korrekt sind, wird in $_SESSION['uid'] die Userid gespeichert.
   *
   * @return bool <b>true</b> gdw. die Daten in der Person-Tabelle hinterlegt sind
   * @access public
   * @author Tom (15.12.04)
   */
  function checkLogin() {
    if (!isset($_SESSION['uname']) || !isset($_SESSION['password'])) {
      return $this->error('checkLogin (Session: User oder Passwort nicht gesetzt)');
    }
    $s = 'SELECT  id, email, password'.
        ' FROM    Person'.
        ' WHERE   email = \''.$_SESSION['uname'].'\''.
        ' AND     password = \''.$_SESSION['password'].'\'';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $_SESSION['uid'] = $data[0]['id'];
      return true;
    }
    return $this->error('checkLogin '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein ConferenceDetailed-Objekt mit Konferenzdaten der aktuellen
   * zurueck.
   *
   * @return ConferenceDetailed [] bzw. <b>false</b>, falls die Konferenz-ID
   *         ungueltig ist.
   * @access public
   * @author Tom (08.01.05)
   */
   
  /* TODO: Wie ist mit Booleans ($blnAuto...) zu verfahren? */
   
  function getConferenceDetailed() {
    $s = 'SELECT  c.id, name, homepage, description, abstract_submission_deadline,'.
        '         paper_submission_deadline, review_deadline, final_version_deadline,'.
        '         notification, conference_start, conference_end, min_reviews_per_paper,'.
        '         default_reviews_per_paper, min_number_of_papers, max_number_of_papers,'.
        '         critical_variance, auto_activate_account, auto_open_paper_forum,'.
        '         auto_add_reviewers, number_of_auto_add_reviewers'.
        ' FROM    Conference AS c'.
        ' INNER   JOIN ConferenceConfig AS cc'.
        ' ON      c.id = cc.id'.
        ' WHERE   c.id = '.$_SESSION['confid'];
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      // hier: Fehler, wenn kein Kriterium bzw. Topic festgelegt wurde (eigentlich Unsinn!)
      /*
      $objCriterions = $this->getCriterionsOfConference();
      if (empty($objCriterions)) {
        return $this->error('getConferenceDetailed '.$this->getLastError());
      }
      $objTopics = $this->getTopicsOfConference();
      if (empty($objTopics)) {
        return $this->error('getConferenceDetailed '.$this->getLastError());
      }
      */
      // erstmal: schweigend hinnehmen
      $objCriterions = $this->getCriterionsOfConference();
      $objTopics = $this->getTopicsOfConference();

      return (new ConferenceDetailed($data[0]['id'], $data[0]['name'], $data[0]['homepage'],
                    $data[0]['description'], $data[0]['conference_start'],
                    $data[0]['conference_end'], $data[0]['abstract_submission_deadline'],
                    $data[0]['paper_submission_deadline'], $data[0]['review_deadline'],
                    $data[0]['final_version_deadline'], $data[0]['notification'],
                    $data[0]['min_reviews_per_paper'], $data[0]['default_reviews_per_paper'],
                    $data[0]['min_number_of_papers'], $data[0]['max_number_of_papers'],
                    $data[0]['critical_variance'], $data[0]['auto_activate_account'],
                    $data[0]['auto_open_paper_forum'], $data[0]['auto_add_reviewers'],
                    $data[0]['number_of_auto_add_reviewers'], $objCriterions, $objTopics));
    }
    return $this->error('getConferenceDetailed '.$this->mySql->getLastError());
  }

  /**
   * Liefert einen Array von Criterion-Objekten zurueck, die Bewertungskriterien
   * der aktuellen Konferenz sind.
   *
   * @return Criterion [] bzw. <b>false</b>, falls keine Konferenz aktiv ist, oder
   *                      ein leeres Array, falls fuer die aktuelle Konferenz keine
   *                      Bewertungskriterien definiert sind.
   * @access public
   * @author Sandro (18.12.04)
   */
  function getCriterionsOfConference() {
    $s = 'SELECT  id, name, description, max_value, quality_rating'.
        ' FROM    Criterion'.
        ' WHERE   conference_id = '.$_SESSION['confid'];
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objCriterions = array();
      for ($i = 0; $i < count($data); $i++) {
        $fltWeight = $data[$i]['quality_rating'] / 100.0;
        $objCriterions[] = (new Criterion($data[$i]['id'], $data[$i]['name'],
                              $data[$i]['description'], $data[$i]['max_value'], $fltWeight));
      }
      return $objCriterions;
    }
    return $this->error('getCriterionsOfConference '.$this->mySql->getLastError());
  }

  /**
   * Liefert einen Array von Topic-Objekten zurueck, die als Topics der
   * aktuellen Konferenz definiert sind.
   *
   * @return Topic [] bzw. <b>false</b>, falls keine Konferenz aktiv ist, oder
   *                  ein leeres Array, falls fuer die aktuelle Konferenz keine
   *                  Topics definiert sind.
   * @access public
   * @author Sandro (18.12.04)
   */
  function getTopicsOfConference() {
    $s = 'SELECT  id, name'.
        ' FROM    Topic'.
        ' WHERE   conference_id = '.$_SESSION['confid'];
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objTopics = array();
      for ($i = 0; $i < count($data); $i++) {
        $objTopics[] = (new Topic($data[$i]['id'], $data[$i]['name']));
      }
      return $objTopics;
    }
    return $this->error('getTopicsOfConference '.$this->mySql->getLastError());
  }

  /**
   * Liefert die ID der Person, deren E-Mail-Adresse $strEmail ist.
   *
   * @param string $strEmail E-Mail-Adresse der Person
   * @return int ID bzw. <b>false</b>, falls keine Person mit E-Mail-Adresse
   *             $strEmail gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
   */
  function getPersonIdByEmail($strEmail) {
    $s = 'SELECT  id'.
        ' FROM    Person'.
        ' WHERE   email = \''.$strEmail.'\'';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      return $data[0]['id'];
    }
    return $this->error('getPersonByEmail '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Person-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @return Person <b>false</b>, falls keine Person mit ID $intPersonId
   *                gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
   */
  function getPerson($intPersonId) {
    $s = 'SELECT  id, first_name, last_name, email, title'.
        ' FROM    Person'.
        ' WHERE   id = '.$intPersonId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $s = 'SELECT  role_type'.
          ' FROM    Role'.
          ' WHERE   person_id = '.$data[0]['id'];
      $role_data = $this->mySql->select($s);
      $role_type = 0;
      if (!empty($role_data)) {
        for ($i = 0; $i < count($role_data); $i++) {
          $role_type = $role_type | (1 << $role_data[$i]['role_type']);
        }
      }
      return (new Person($data[0]['id'], $data[0]['first_name'], $data[0]['last_name'],
                $data[0]['email'], $role_type, $data[0]['title']));
    }
    return $this->error('getPerson '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein PersonDetailed-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @return PersonDetailed <b>false</b>, falls keine Person mit ID $intPersonId
   *                        gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
   */
  function getPersonDetailed($intPersonId) {
    $s = 'SELECT  id, first_name, last_name, email, title, affiliation,'.
        '         street, city, postal_code, state, country, phone_number,'.
        '         fax_number'.
        ' FROM    Person'.
        ' WHERE   id = '.$intPersonId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $s = 'SELECT  role_type'.
          ' FROM    Role'.
          ' WHERE   person_id = '.$data[0]['id'];
      $role_data = $this->mySql->select($s);
      $role_type = 0;
      if (!empty($role_data)) {
        for ($i = 0; $i < count($role_data); $i++) {
          $role_type = $role_type | (1 << $role_data[$i]['role_type']);
        }
      }
      return (new PersonDetailed($data[0]['id'], $data[0]['first_name'],
                $data[0]['last_name'], $data[0]['email'], $role_type,
                $data[0]['title'], $data[0]['affiliation'], $data[0]['street'],
                $data[0]['city'], $data[0]['postal_code'], $data[0]['state'],
                $data[0]['country'], $data[0]['phone_number'],
                $data[0]['fax_number']));
    }
    return $this->error('getPersonDetailed '.$this->mySql->getLastError());
  }

  /**
   * Liefert das PaperSimple-Objekt mit der ID $intPaperId zurueck.
   *
   * @param int $intPaperId ID des Papers
   * @return PaperSimple <b>false</b>, falls das Paper nicht existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getPaper($intPaperId) {
    $s = 'SELECT  id, author_id, title, state'.
        ' FROM    Paper'.
        ' WHERE   id = '.$intPaperId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $fltAvgRating = $this->getAverageRatingOfPaper($intPaperId);
      $objAuthor = $this->getPerson($data[$i]['author_id']);
      if (empty($objAuthor)) {
        return $this->error('getPaper '.$this->mySql->getLastError());
      }
      $strAuthor = $objAuthor->getName();
      return (new PaperSimple($intPaperId, $data[0]['title'],
                $data[0]['author_id'], $strAuthor, $data[0]['state'], $fltAvgRating,
                $this->getTopicsOfPaper($intPaperId)));
    }
    return $this->error('getPaper '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten des Autors $intAuthorId.
   *
   * @param int $intAuthorId ID des Autors
   * @return PaperSimple [] <b>false</b>, falls keine Paper des Autors
   *                        $intAuthorId gefunden wurden
   * @access public
   * @author Tom (04.12.04, 12.12.04)
   */
  function getPapersOfAuthor($intAuthorId) {
    $s = 'SELECT  id, author_id, title, state'.
        ' FROM    Paper'.
        ' WHERE   author_id = '.$intAuthorId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      for ($i = 0; $i < count($data); $i++) {
        $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
        $objAuthor = $this->getPerson($intAuthorId);
        if (empty($objAuthor)) {
          return $this->error('getPapersOfAuthor '.$this->mySql->getLastError());
        }
        $strAuthor = $objAuthor->getName();
        $objPapers[$i] = new PaperSimple($data[$i]['id'], $data[$i]['title'],
                           $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
                           $fltAvgRating, $this->getTopicsOfPaper($data[$i]['id']));
      }
      return $objPapers;
    }
    return $this->error('getPapersOfAuthor '.$this->mySql->getLastError());
  }


  /**
   * Liefert ein Array von PaperSimple-Objekten des Reviewers $intReviewerId.
   *
   * @param int $intReviewerId ID des Reviewers
   * @return PaperSimple [] <b>false</b>, falls keine Reviews des Reviewers
   *                        $intReviewerId gefunden wurden
   * @access public
   * @author Tom (12.12.04)
   */
  function getPapersOfReviewer($intReviewerId) {
    $s = 'SELECT  p.id AS id, author_id, title, state'.
        ' FROM    Paper AS p'.
        ' INNER   JOIN ReviewReport AS r'.
        ' ON      r.paper_id = p.id'.
        ' AND     r.reviewer_id = '.$intReviewerId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      for ($i = 0; $i < count($data); $i++) {
        $objAuthor = $this->getPerson($intReviewerId);
        if (empty($objAuthor)) {
          return $this->error('getPapersOfReviewer '.$this->mySql->getLastError());
        }
        $strAuthor = $objAuthor->getName();
        $fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
        $objPapers[$i] = new PaperSimple($data[$i]['id'], $data[$i]['title'],
                           $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
                           $fltAvgRating, $this->getTopicsOfPaper($data[$i]['id']));
      }
      return $objPapers;
    }
    return $this->error('getPapersOfReviewer '.$this->mySql->getLastError());
  }

  /**
   * Liefert PaperDetailed-Objekt mit den Daten des Papers $intPaperId.
   *
   * @param int $intPaperId ID des Papers
   * @return PaperDetailed <b>false</b>, falls kein Paper mit der ID
   *                       $intPaperId gefunden wurde
   * @access public
   * @author Tom (12.12.04)
   */
  function getPaperDetailed($intPaperId) {
    $s = 'SELECT  author_id, title, state, abstract, format, last_edited, filename'.
        ' FROM    Paper'.
        ' WHERE   id = '.$intPaperId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objAuthor = $this->getPerson($data[0]['author_id']);
      if (empty($objAuthor)) {
        return $this->error('getPaperDetailed '.$this->mySql->getLastError());
      }
      $strAuthor = $objAuthor->getName();
      $fltAvgRating = $this->getAverageRatingOfPaper($intPaperId);
      // Co-Autoren
      $s = 'SELECT  person_id AS coauthor_id, name'.
          ' FROM    IsCoAuthorOf AS i'.
          ' LEFT    JOIN Person AS p'.
          ' ON      p.id = i.person_id'.
          ' WHERE   paper_id = '.$intPaperId.
          ' ORDER   BY person_id DESC'; // ORDER BY: Co-Autoren im System im Array vorne!
      $cadata = $this->mySql->select($s);
      $intCoAuthorIds = array();
      $strCoAuthors = array();
      if (!empty($cadata)) {
        for ($i = 0; $i < count($cadata); $i++) {
          $intCoAuthorIds[$i] = $cadata[$i]['coauthor_id'];
          $objCoAuthor = $this->getPerson($cadata[$i]['coauthor_id']);
          if (empty($objCoAuthor)) { // d.h. Co-Autor nicht im System => nimm Name aus Tabelle
            $strCoAuthors[$i] = $cadata[$i]['name'];
          }
          else {
            $strCoAuthors[$i] = $objCoAuthor->getName();
          }
        }
      }
      return (new PaperDetailed($intPaperId, $data[0]['title'], $data[0]['author_id'],
                $strAuthor, $data[0]['state'], $fltAvgRating, $intCoAuthorIds,
                $strCoAuthors, $data[0]['abstract'], $data[0]['mime_type'],
                $data[0]['last_edited'], $data[0]['filename'],
                $this->getTopicsOfPaper($intPaperId)));
    }
    return $this->error('getPaperDetailed '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Topic-Objekten der Themen zurueck, die mit dem
   * Paper $intPaperId assoziiert sind.
   *
   * @param int $intPaperId ID des Papers
   * @return Topic [] <b>false</b>, falls das Paper nicht existiert.
   *                   Gibt ein leeres Array zurueck, wenn das Paper mit keinen
   *                   Themen assoziiert ist.
   * @access public
   * @author Sandro, Tom (17.12.04)
   */
  function getTopicsOfPaper($intPaperId) {
    $s = 'SELECT  t.id, t.name'.
        ' FROM    Topic AS t'.
        ' INNER   JOIN IsAboutTopic AS a'.
        ' ON      a.topic_id = t.id'.
        ' WHERE   a.paper_id = '.$intPaperId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objTopics = array();
      for ($i = 0; $i < count($data); $i++) {
        $objTopics[] = (new Topic($data[$i]['id'], $data[$i]['name']));
      }
      return $objTopics;
    }
    return $this->error('getTopicsOfPaper '.$this->mySql->getLastError());
  }

  /**
   * Liefert den Durchschnitt der Gesamtbewertungen des Papers $intPaperId.
   *
   * @param int $intPaperId ID des Papers
   * @return float <b>false</b>, falls keine Bewertungen des Papers gefunden wurden.
   * @access private
   * @author Sandro, Tom (06.12.04, 12.12.04)
   */
  function getAverageRatingOfPaper($intPaperId) {
    $s = 'SELECT  SUM(((r.grade-1)/(c.max_value-1))*(c.quality_rating/100)) AS total_rating'.
        ' FROM    ReviewReport AS rr'.
        ' INNER   JOIN Rating AS r'.
        ' ON      r.review_id = rr.id'.
        ' INNER   JOIN Criterion AS c'.
        ' ON      c.id = r.criterion_id'.
        ' WHERE   rr.paper_id = '.$intPaperId.
        ' GROUP   BY rr.id';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $sum = 0;
      for ($i = 0; $i < count($data); $i++) {
        $sum += $data[$i]['total_rating'];
      }
      return $sum / count($data);
    }
    return $this->error('getAverageRatingOfPaper '.$this->mySql->getLastError());
  }

  /**
   * Liefert die Gesamtbewertung eines Reviews (die Durchschnittsnote aller
   * Kriterien unter Beruecksichtigung der Gewichtungen).
   *
   * @param int $intReviewId ID des Reviews
   * @return int <b>false</b>, falls das Review nicht existiert
   * @access private
   * @author Sandro, Tom (06.12.04, 12.12.04)
   */
  function getReviewRating($intReviewId) {
    $s = 'SELECT  SUM(((r.grade-1)/(c.max_value-1))*(c.quality_rating/100)) AS total_rating'.
        ' FROM    Rating AS r'.
        ' INNER   JOIN Criterion AS c'.
        ' ON      c.id = r.criterion_id'.
        ' AND     r.review_id = '.$intReviewId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      return $data[0]['total_rating'];
    }
    return $this->error('getReviewRating '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Review-Objekten des Reviewers $intReviewerId zurueck.
   *
   * @param int $intReviewerId ID des Reviewers
   * @return Review <b>false</b>, falls kein Review des Reviewers existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewsOfReviewer($intReviewerId) {
    $s = 'SELECT  id'.
        ' FROM    ReviewReport'.
        ' WHERE   reviewer_id = '.$intReviewerId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objReviews = array();
      for ($i = 0; $i < count($data); $i++) {
        $objReviews[] = $this->getReview($data[i]['id']);
      }
    }
    return $this->error('getReviewsOfReviewer '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Person-Objekten zurueck, die Reviewer des Papers $intPaperId sind.
   *
   * @param int $intPaperId ID des Papers
   * @return Person [] <b>false</b>, falls das Paper keine Reviewer besitzt
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewersOfPaper($intPaperId) {
    $s = 'SELECT  reviewer_id'.
        ' FROM    ReviewReport'.
        ' WHERE   paper_id = '.$intPaperId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objReviewers = array();
      for ($i = 0; $i < count($data); $i++) {
        $objReviewers[] = $this->getPerson($data[i]['reviewer_id']);
      }
    }
    return $this->error('getReviewersOfPaper '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Review-Objekten des Papers $intPaperId zurueck.
   *
   * @param int $intPaperId ID des Papers
   * @return Review [] <b>false</b>, falls kein Review des Papers existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewsOfPaper($intPaperId) {
    $s = 'SELECT  id'.
        ' FROM    ReviewReport'.
        ' WHERE   paper_id = '.$intPaperId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objReviews = array();
      for ($i = 0; $i < count($data); $i++) {
        $objReviews[] = $this->getReview($data[i]['id']);
      }
    }
    return $this->error('getReviewsOfPaper '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Review-Objekt mit den Daten des Reviews $intReviewId zurueck.
   *
   * @param int $intReviewId ID des Reviews
   * @return Review <b>false</b>, falls das Review, oder das assoziierte Paper,
   *                der Autor oder der Reviewer nicht existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReview($intReviewId) {
    $s = 'SELECT  id, paper_id, reviewer_id'.
        ' FROM    ReviewReport'.
        ' WHERE   id = '.$intReviewId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objReviewer = $this->getPerson($data[0]['reviewer_id']);
      if (empty($objReviewer)) {
        return $this->error('getReview '.$this->mySql->getLastError());
      }
      $objPaper = $this->getPaper($data[0]['paper_id']);
      if (empty($objPaper)) {
        return $this->error('getReview '.$this->mySql->getLastError());
      }
      $objAuthor = $this->getPerson($objPaper->intAuthorId);
      if (empty($objAuthor)) {
        return $this->error('getReview '.$this->mySql->getLastError());
      }
      return (new Review($data[0]['id'], $data[0]['paper_id'],
                $paper_data[0]['title'], $objAuthor->strEmail, $objAuthor->getName(),
                getReviewRating($intReviewId), getAverageRatingOfPaper($paper_data[0]['id']),
                $objReviewer->strEmail, $objReviewer->getName()));
    }
    return $this->error('getReview '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein ReviewDetailed-Objekt mit den Daten des Reviews $intReviewId zurueck.
   *
   * @param int $intReviewId ID des Reviews
   * @return ReviewDetailed <b>false</b>, falls das Review, oder das assoziierte Paper,
   *                        der Autor oder der Reviewer nicht existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getReviewDetailed($intReviewId) {
    $s = 'SELECT  id, paper_id, reviewer_id, summary, remarks, confidential'.
        ' FROM    ReviewReport'.
        ' WHERE   id = '.$intReviewId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objReviewer = $this->getPerson($data[0]['reviewer_id']);
      if (empty($objReviewer)) {
        return $this->error('getReviewDetailed '.$this->mySql->getLastError());
      }
      $objPaper = $this->getPaper($data[0]['paper_id']);
      if (empty($objPaper)) {
        return $this->error('getReviewDetailed '.$this->mySql->getLastError());
      }
      $objAuthor = $this->getPerson($objPaper->intAuthorId);
      if (empty($objAuthor)) {
        return $this->error('getReviewDetailed '.$this->mySql->getLastError());
      }
      $s = 'SELECT  r.grade, r.comment, c.id, c.name, c.description, c.max_value,'.
          '         c.quality_rating'.
          ' FROM    Rating r'.
          ' INNER   JOIN Criterion c'.
          ' ON      c.id  = r.criterion_id'.
          ' WHERE   review_id = '.$data[0]['id'];
      $rating_data = $this->mySql->select($s);
      $intRatings = array();
      $strComments = array();
      $objCriterions = array();
      if (!empty($rating_data)) {
        for ($i = 0; $i < count($rating_data); $i++) {
          $intRatings[] = $rating_data[$i]['grade'];
          $strComments[] = $rating_data[$i]['comment'];
          $objCriterions[] = (new Criterion($rating_data[$i]['id'], $rating_data[$i]['name'],
                                $rating_data[$i]['description'], $rating_data[$i]['max_value'],
                                $rating_data[$i]['quality_rating'] / 100.0));
        }
      }
      return (new ReviewDetailed($data[0]['id'], $data[0]['paper_id'],
                $paper_data[0]['title'], $objAuthor->strEmail, $objAuthor->getName(),
                getReviewRating($intReviewId), getAverageRatingOfPaper($paper_data[0]['id']),
                $objReviewer->strEmail, $objReviewer->getName(),
                $data[0]['summary'], $data[0]['remarks'], $data[0]['confidential'],
                $intRatings, $strComments, $objCriterions));
    }
    return $this->error('getReviewDetailed '.$this->mySql->getLastError());
  }

  /**
   */
  function checkAccessToForum($intForumId) {
    return false;
  }

  /**
   * Liefert ein Array von Message-Objekten zurueck, die (direkte) Antworten auf die
   * Message $intMessageId sind.
   *
   * @param int $intMessageId ID der Message
   * @return Message [] <b>false</b>, falls die Message nicht existiert oder
   *                    ein leeres Array, wenn die Message keine Antworten besitzt
   * @access private
   * @author Sandro (14.12.04)
   */
  function getNextMessages($intMessageId) {
    $s = 'SELECT  id, sender_id, send_time, subject, text'.
        ' FROM    Message'.
        ' WHERE   reply_to = '.$intMessageId;
    $data = $this->mySql->select($s);
    $messages = array();
    if (!empty($data)) {
      for ($i = 0; $i < count($data); $i++) {
        $messages[] = (new Message($data[$i]['id'], $data[$i]['sender_id'],
                         $data[$i]['send_time'], $data[$i]['subject'],
                         $data[$i]['text'], $this->getNextMessages($data[$i]['id'])));
      }
      return $messages;
    }
    return $this->error('getNextMessages '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Message-Objekten zurueck, welche die Wurzelknoten
   * von Threads des Forums $intForumId sind (im folgenden synonym mit Thread verwendet).
   *
   * @param int $intForumId ID des Forums
   * @return Message[] <b>false</b>, falls das Forum nicht existiert oder
   *                   ein leeres Array, wenn das Forum keine Threads besitzt
   * @access public
   * @author Sandro (14.12.04)
   */
  function getThreadsOfForum($intForumId) {
    $s = 'SELECT  id, sender_id, send_time, subject, text'.
        ' FROM    Message'.
        ' WHERE   forum_id = '.$intForumId.
        ' AND     reply_to IS NULL';
    $data = $this->mySql->select($s);
    $objThreads = array();
    if (!empty($data)) {
      for ($i = 0; $i < count($data); $i++) {
        $objThreads[] = (new Message($data[$i]['id'], $data[$i]['sender_id'],
                           $data[$i]['send_time'], $data[$i]['subject'],
                           $data[$i]['text'], $this->getNextMessages($data[$i]['id'])));
      }
      return $objThreads;
    }
    return $this->error('getThreadsOfForum '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Forum-Objekten der aktuellen Konferenz zurueck.
   *
   * @return Forum [] <b>false</b>, falls kein Forum existiert oder keine Konferenz
   *                  in der aktuellen Session aktiv ist
   * @access public
   * @author Sandro (14.12.04)
   */
  function getAllForums() {
    $s = 'SELECT  id, title'.
        ' FROM    Forum'; //.
    //    ' WHERE   conferenceId = '.$_SESSION['confid'];
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $objForums = array();
      for ($i = 0; $i < count($data); $i++) {
        $objForums[] = (new Forum($data[$i]['id'], $data[$i]['title'], $data[$i]['forum_type'],
                          ($data[$i]['forum_type'] == 3) ? $data[$i]['paper_id'] : 0));
          // [TODO] statt '3' die Konstante fuer den Artikelforen-Typ!
      }
      return $objForums;
    }
    return $this->error('getAllForums '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Forum-Objekt des Forums zurueck, das mit Paper $intPaperId assoziiert ist.
   *
   * @return Forum <b>false</b>, falls das Paper nicht existiert oder kein Forum besitzt
   * @access public
   * @author Sandro (14.12.04)
   */
  function getForumOfPaper($intPaperId) {
    $s = 'SELECT  id, title'.
        ' FROM    Forum'.
        ' WHERE   paperId = '.$intPaperId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $forum = (new Forum($data[$i]['id'], $data[$i]['title'], 0, false));
      return $forum;
    }
    return $this->error('getForumOfPaper '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein Array von Forum-Objekten aller Foren zurueck, welche die Person
   * $intPersonId einsehen darf.
   *
   * @return Forum [] <b>false</b>, falls die Person nicht existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getForumsOfPerson($intPersonId) {
    $objPerson = getPerson($intPersonId);
    if (!empty($objPerson)) {
      $objAllForums = getAllForums();
      $objForums = array();
      if (!empty($objAllForums)) {
        for ($i = 0; $i < count($objAllForums); $i++) {
          if ($objAllForums[$i]->isPersonAllowed($objPerson)) {
            $objForums[] = $objAllForums[$i];
          }
        }
        return $objForums;
      }
    }
    return $this->error('getForumsOfPerson '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein ForumDetailed-Objekt mit den Daten des Forums $intForumId zurueck.
   * Das ForumDetailed-Objekt enthaelt den kompletten Message-Baum des Forums.
   *
   * @return ForumDetailed <b>false</b>, falls das Forum nicht existiert
   * @access public
   * @author Sandro (14.12.04)
   */
  function getForumDetailed($intForumId) {
    $s = 'SELECT  id, title'.
        ' FROM    Forum'.
        ' WHERE   id = '.$intForumId;
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      $forum = (new ForumDetailed($data[0]['id'], $data[0]['title'],
                  0, false, $this->getThreadsOfForum($intForumId)));
      return $forum;
    }
    return $this->error('getForumDetailed '.$this->mySql->getLastError());
  }


  // ---------------------------------------------------------------------------
  // Definition der Update-Funktionen
  // ---------------------------------------------------------------------------


  // ---------------------------------------------------------------------------
  // Definition der Insert-Funktionen
  // ---------------------------------------------------------------------------

  /**
   * Legt eine neue Konferenz an.
   * @param $strName                   :max. 127 Zeichen
   * @param $strHomepage               :max. 127 Zeichen
   * @param $strDescription       
   * @param $strAbsSubDeadline         :?
   * @param $strPaperSubDeadline       :?
   * @param $strReviewDeadline         :?
   * @param $strFinalVersionDeadline   :?
   * @param $strNotification           :?
   * @param $strConverenceStart        :?
   * @param $strConferenceEnd          :?
   * @param $strMinRevPerPaper         :?
   * 
   * Datum wird übergeben im Format Jahr-Monat-Tag z.B. 2012115 
   *
   * @access public
   * @author Daniel (31.12.04)
   */
   
   /* TODO: automatisch auch neuen ConferenceConfig-Datensatz einfuegen! (Tom) */
   function addConference($strName, $strHomepage, $strDescription, $strAbsSubDeadline, 
                         $strPaperSubDeadline, $strReviewDeadline, $strFinalVersionDeadline, 
                         $strNotification, $strConverenceStart, $strConferenceEnd, $strMinRevPerPaper){
     $s = 'INSERT  INTO Conference (name, homepage, description, abstract_submission_deadline, 
                                    paper_submission_deadline, review_deadline, final_version_deadline,
                                    notification, conference_start, conference_end, min_reviews_per_paper)'. 
                 ' VALUES          ( \''.$strName.'\', 
                                     \''.$strHomepage.'\',
                                     \''.$strDescription.'\',
                                     \''.$strAbsSubDeadline.'\',
                                     \''.$strPaperSubDeadline.'\',
                                     \''.$strReviewDeadline.'\',
                                     \''.$strFinalVersionDeadline.'\',
                                     \''.$strNotification.'\',
                                     \''.$strConverenceStart.'\',
                                     \''.$strConferenceEnd.'\',
                                     \''.$strMinRevPerPaper.'\')';
     //echo('<br>SQL: '.$s.'<br>');
     $intId = $this->mySql->insert($s);
     if (!empty($intId)) {
       return $intId;
     }
     else {
       return false;
       echo $this->error('addConference '.$this->mySql->getLastError());
    }
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
   * @param string $strCountry      Land des Wohnsitzes
   * @param string $strPhone        Telefonnummer der Person
   * @param string $strFax          Faxnummer der Person
   * @param string $strPassword     Passwort des Accounts (unverschluesselt zu uebergeben)
   * @return int ID der erzeugten Person oder false , falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro, Tom (17.12.04)
   */
  function addPerson($strFirstname, $strLastname, $strEmail, $strTitle,
                     $strAffiliation, $strStreet, $strCity, $strPostalCode,
                     $strState, $strCountry, $strPhone, $strFax, $strPassword) {
    $s = 'INSERT  INTO Person (first_name, last_name, title, affiliation, email,'.
        '                      street, postal_code, city, state, country,'.
        '                      phone_number, fax_number, password)'.
        '         VALUES (\''.$strFirstname.'\', \''.$strLastname.'\', \''.$strTitle.'\','.
        '                 \''.$strAffiliation.'\', \''.$strEmail.'\', \''.$strStreet.'\','.
        '                 \''.$strPostalCode.'\', \''.$strCity.'\', \''.$strState.'\','.
        '                 \''.$strCountry.'\', \''.$strPhone.'\', \''.$strFax.'\','.
        '                 \''.sha1($strPassword).'\')';
    // echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      return $intId;
    }
    else {
      return false;
      echo $this->error('addPerson '.$this->mySql->getLastError());
    }
  }

  /**
   * Fuegt der Person $intPersonId die Rolle $intRole hinzu.
   *
   * @param int $intConferenceId Konferenz-ID
   * @param int $intPersonId     Personen-ID
   * @param int $intRole         Rollen-Enum
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function addRole($intConferenceId, $intPersonId, $intRoleType) {
    $s = 'INSERT  INTO Role (conference_id, person_id, role_type)'.
        '         VALUES (\''.$intConferenceId.'\', \''.$intPersonId.'\','.
        '                 \''.$intRoleType.'\')';
    // echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return true;
    }
    return $this->error('addRole '.$this->mySql->getLastError());
  }

  /**
   * Fuegt dem Paper $intPaperId den Co-Autor $intCoAuthorId hinzu.
   *
   * @param int $intPaperId    Paper-Id
   * @param int $intCoAuthorId Co-Autor-ID
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function addCoAuthor($intPaperId, $intCoAuthorId) {
    $s = 'INSERT  INTO IsCoAuthorOf (person_id, paper_id)'.
        '         VALUES (\''.$intCoAuthorId.'\', \''.$intPaperId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return true;
    }
    return $this->error('addCoAuthor '.$this->mySql->getLastError());
  }

  /**
   * Fuegt dem Paper $intPaperId den Namen $strName eines Co-Autoren hinzu,
   * der nicht als Person im System vorkommt.
   *
   * @param int $intPaperId Paper-ID
   * @param str $strName    Name des Co-Autors
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function addCoAuthorName($intPaperId, $strName) {
    $s = 'INSERT  INTO IsCoAuthorOf (paper_id, name)'.
        '         VALUES (\''.$intPaperId.'\', \''.$strName.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return true;
    }
    return $this->error('addCoAuthorName '.$this->mySql->getLastError());
  }

  /**
   * Fuegt ein Paper (mit den Namen der Co-Autoren) hinzu.
   * TODO: Fehler beim Einfuegen von Co-Autoren? => UNDO Vorheriges
   *
   * @param int $intConferenceId Konferenz-ID
   * @param int $intAuthorId     Autor-ID
   * @param str $strTitle        Titel
   * @param str $strAbstract     Text des Abstracts
   * @param str $strFilePath     Dateipfad und -name
   * @param str $strMimeType     MIME-Typ
   * @param str $strCoAuthors[]  Namen der Co-Autoren
   * @return int|bool ID des erzeugten Papers bzw. <b>false</b>, falls ein Fehler
   *                  aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function addPaper($intConferenceId, $intAuthorId, $strTitle, $strAbstract,
                    $strFilePath, $strMimeType, $strCoAuthors) {
    $s = 'INSERT  INTO Paper (conference_id, author_id, title, abstract, filename,'.
        '                     mime_type, state)'.
        '         VALUES (\''.$intConferenceId.'\', \''.$intAuthorId.'\', \''.$strTitle.'\','.
        '                 \''.$strAbstract.'\', \''.$strFilePath.'\', \''.$strMimeType.'\', 0)';
    echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      $blnOk = true;
      for ($i = 0; $i < count($strCoAuthors); $i++) {
        if ($this->addCoAuthorName($intId, $strCoAuthors[$i]) == false) {
          $blnOk = false;
        }
      }
      if ($blnOk) {
        return $intId;
      }
      return $this->error('addPaper '.$this->getLastError());
    }
    return $this->error('addPaper '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle ReviewReport ein.
   *
   * @param int $intPaperId          ID des Papers, das bewertet wird
   * @param int $intReviewerId       ID des Reviewers
   * @param string $strSummary       Zusammenfassender Text fuer die Bewertung (inital: '')
   * @param string $strRemarks       Anmerkungen fuer den Autoren (inital: '')
   * @param string $strConfidential  Vertrauliche Anmerkungen fuer das Komitee (inital '')
   * @return int ID des erzeugten Review-Reports oder <b>false</b>, falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addReviewReport($intPaperId, $intReviewerId, $strSummary = '',
                           $strRemarks = '', $strConfidential = '') {
    $s = 'INSERT  INTO ReviewReport (paper_id, reviewer_id, summary, remarks, confidential)'.
        '         VALUES (\''.$intPaperId.'\', \''.$intReviewerId.'\','.
        '                 \''.$strSummary.'\', \''.$strRemarks.'\', \''.$strConfidential.'\')';
    return $this->error('addReviewReport '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Rating ein.
   *
   * @param int $intReviewId     ID des Review-Reports, der das Rating beinhaltet
   * @param int $intCriterionId  ID des Bewertungskriteriums
   * @param int $intGrade        Note, Auspraegung der Bewertung (inital: 0)
   * @param string $strComment   Anmerkung zur Bewertung in dem Kriterium (inital: '')
   * @return int ID des erzeugten Ratings oder <b>false</b>, falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addRating($intReviewId, $intCriterionId, $intGrade = 0, $strComment = '') {
    $s = 'INSERT  INTO Rating (review_id, criterion_id, grade, comment)'.
        '         VALUES (\''.$intReviewId.'\', \''.$intCriterionId.'\','.
        '                 \''.$intGrade.'\', \''.$strComment.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      return $intId;
    }
    return $this->error('addRating '.$this->mySql->getLastError());
  }

  /**
   * Erstellt einen neuen Review-Report-Datensatz in der Datenbank, sowie die
   * mit diesem Review-Report assoziierten Ratings (Bewertungen in den einzelnen
   * Kriterien). Initial sind die Bewertungen 0 und die Kommentartexte leer.
   *
   * [TODO] Bleibt evtl. nicht an dieser Stelle stehen, sondern wandert in ein anderes Skript!
   */

   function createNewReviewReport($intPaperId, $intReviewerId) {
     $intConferenceId = $_SESSION['confid'];
     $intReviewId = $this->addReviewReport($intPaperId, $intReviewerId);
     if (empty($intReviewId)) {
        return $this->error('createNewReviewReport '.$this->mySql->getLastError());
     }
     $objCriterions = $this->getCriterionsOfConference($intConferenceId);
     for ($i = 0; $i < count($objCriterions); $i++) {
        $this->addRating($intReviewId, $objCriterions[$i]->intId, 0, '');
     }
     return $intReviewId;
   }

  /**
   * Fuegt einen Datensatz in die Tabelle Forum ein.
   *
   * @param int $intConferenceId  ID der Konferenz, fuer die das Forum angelegt wird
   * @param string $strTitle      Bezeichnung des Forums
   * @param int $intForumType     Art des Forums (1: globales, 2:Komitee-, 3:Artikelforum)
   * @param int $intPaperId       ID des assoziierten Artikels bei Artikelforen (sonst: 0)
   * @return int ID des erzeugten Forums oder <b>false</b>, falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addForum($intConferenceId, $strTitle, $intForumType, $intPaperId = 0) {
    if ($intForumType <> 3) {  // [TODO] statt '3' die Konstante fuer den Artikelforen-Typ!
      $intPaperId = 0;
    }
    $s = 'INSERT  INTO Forum (conference_id, title, forum_type, paper_id)'.
        '         VALUES (\''.$intConferenceId.'\', \''.$strTitle.'\','.
        '                 \''.$intForumType.'\', \''.$intPaperId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      return $intId;
    }
    return $this->error('addForum '.$this->mySql->getLastError());
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
   * @return int ID der erzeugten Message oder <b>false</b>, falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addMessage($strSubject, $strText, $strSenderId, $strForumId, $strReplyTo = 0) {
    $strSendTime = date("d.m.Y H:i");
    $s = 'INSERT  INTO Message (subject, text, sender_id, forum_id, reply_to, send_time)'.
        '         VALUES (\''.$strSubject.'\', \''.$strText.'\','.
        '                 \''.$intSenderId.'\', \''.$intForumId.'\','.
        '                 \''.$intReplyTo.'\', \''.$strSendTime.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      return $intId;
    }
    return $this->error('addMessage '.$this->mySql->getLastError());
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
   * @return int ID des erzeugten Bewertungskriteriums oder <b>false</b>, falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addCriterion($intConferenceId, $strName, $strDescription, $intMaxValue, $fltWeight) {
    $intQualityRating = $fltWeight * 100;
    $s = 'INSERT  INTO Criterion (conference_id, name, description, max_value, quality_rating)'.
        '         VALUES (\''.$intConferenceId.'\', \''.$strName.'\', \''.$strDescription.'\','.
        '                 \''.$intMaxValue.'\', \''.$intQualityRating.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      return $intId;
    }
    return $this->error('addCriterion '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle Topic ein.
   *
   * @param int $intConferenceId  ID der Konferenz, fuer die das Topic angelegt wird
   * @param string $strName       Bezeichnung des Topics
   * @return int ID des erzeugten Topics oder <b>false</b>, falls ein Fehler
   *             aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addTopic($intConferenceId, $strName) {
    $s = 'INSERT  INTO Topic (conference_id, name)'.
        '         VALUES (\''.$intConferenceId.'\', \''.$strName.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $intId = $this->mySql->insert($s);
    if (!empty($intId)) {
      return $intId;
    }
    return $this->error('addTopic '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle IsAboutTopic ein.
   *
   * @param int $intPaperId  ID des Papers
   * @param int $intTopicId  ID des behandelten Topics
   * @return int <b>false</b>, falls ein Fehler aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addIsAboutTopic($intPaperId, $intTopicId) {
    $s = 'INSERT  INTO IsAboutTopic (paper_id, topic_id)'.
        '         VALUES (\''.$intPaperId.'\', \''.$intTopicId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('addIsAboutTopic '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle PrefersTopic ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intTopicId   ID des bevorzugten Topics
   * @return int <b>false</b>, falls ein Fehler aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addPrefersTopic($intPersonId, $intTopicId) {
    $s = 'INSERT  INTO PrefersTopic (person_id, topic_id)'.
        '         VALUES (\''.$intPersonId.'\', \''.$intTopicId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('addPrefersTopic '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle PrefersPaper ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intPaperId   ID des bevorzugten Papers
   * @return int <b>false</b>, falls ein Fehler aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addPrefersPaper($intPersonId, $intPaperId) {
    $s = 'INSERT  INTO PrefersPaper (person_id, paper_id)'.
        '         VALUES (\''.$intPersonId.'\', \''.$intPaperId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('addPrefersPaper '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle DeniesPaper ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intPaperId   ID des abgelehnten Papers
   * @return int <b>false</b>, falls ein Fehler aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addDeniesPaper($intPersonId, $intPaperId) {
    $s = 'INSERT  INTO DeniesPaper (person_id, paper_id)'.
        '         VALUES (\''.$intPersonId.'\', \''.$intPaperId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('addDeniesPaper '.$this->mySql->getLastError());
  }

  /**
   * Fuegt einen Datensatz in die Tabelle ExcludesPaper ein.
   *
   * @param int $intPersonId  ID der Person
   * @param int $intPaperId   ID des ausgeschlossenen Papers
   * @return int <b>false</b>, falls ein Fehler aufgetreten ist
   * @access public
   * @author Sandro (18.12.04)
   */
  function addExcludesPaper($intPersonId, $intPaperId) {
    $s = 'INSERT  INTO ExcludesPaper (person_id, paper_id)'.
        '         VALUES (\''.$intPersonId.'\', \''.$intPaperId.'\')';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->insert($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('addExcludesPaper '.$this->mySql->getLastError());
  }


  // ---------------------------------------------------------------------------
  // Definition der Delete-Funktionen
  // ---------------------------------------------------------------------------

  /**
   * Loescht die Konferenz mit der ID $intConferenceId.
   *
   * @param int $intConferenceId Konferenz-ID
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteConference($intConferenceId) {
    $s = 'DELETE  FROM Conference'.
        ' WHERE   id = '.$intConferenceId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteConference '.$this->mySql->getLastError());
  }

  /**
   * Loescht die Person mit der ID $intPersonId.
   *
   * @param int $intPersonId Personen-ID
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function deletePerson($intPersonId) {
    $s = 'DELETE  FROM Person'.
        ' WHERE   id = '.$intPersonId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deletePerson '.$this->mySql->getLastError());
  }

  /**
   * Loescht das Person mit der ID $intPaperId.
   *
   * @param int $intPaperId Paper-ID
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function deletePaper($intPaperId) {
    $s = 'DELETE  FROM Paper'.
        ' WHERE   id = '.$intPaperId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deletePaper '.$this->mySql->getLastError());
  }

  /**
   * Loescht die Rolle $intRoleType der Person $intPersonId fuer die Konferenz
   * $intConferenceId.
   *
   * @param int $intConferenceId Konferenz-ID
   * @param int $intPersonId     Personen-ID
   * @param int $intRoleType     Rollen-Enum
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteRole($intConferenceId, $intPersonId, $intRoleType) {
    $s = 'DELETE  FROM Role'.
        ' WHERE   conference_id = '.$intConferenceId.
        ' AND     person_id = '.$intPersonId.
        ' AND     role_type = '.$intRoleType;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteRole '.$this->mySql->getLastError());
  }

  /**
   * Loescht den Co-Autor $intCoAuthorId vom Paper $intPaperId.
   *
   * @param int $intPaperId    Paper-Id
   * @param int $intCoAuthorId Co-Autor-ID
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteCoAuthor($intPaperId, $intCoAuthorId) {
    $s = 'DELETE  FROM IsCoAuthorOf'.
        ' WHERE   person_id = '.$intCoAuthorId.
        ' AND     paper_id = '.$intPaperId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteCoAuthor '.$this->mySql->getLastError());
  }

  /**
   * Loescht den Co-Autor mit Namen $strName vom Paper $intPaperId.
   *
   * @param int $intPaperId Paper-Id
   * @param int $strName    Name des Co-Autors
   * @return bool <b>false</b> gdw. ein Fehler aufgetreten ist
   * @access public
   * @author Tom (26.12.04)
   */
  function deleteCoAuthorName($intPaperId, $strName) {
    $s = 'DELETE  FROM IsCoAuthorOf'.
        ' WHERE   paper_id = '.$intPaperId.
        ' AND     name = \''.$strName.'\'';
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteCoAuthor '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteReviewReport($intReviewId) {
    $s = 'DELETE  FROM Reviewreport'.
        '         WHERE id = '.$intReviewId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteReviewReport '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteRating($intReviewId, $intCriterionId) {
    $s = 'DELETE  FROM Rating'.
        '         WHERE   review_id = '.$intReviewId.
        '         AND     criterion_id = '.$intCriterionId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteRating '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteForum($intForumId) {
    $s = 'DELETE  FROM Forum'.
        '         WHERE id = '.$intForumId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteForum '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteMessage($intMessageId) {
    $s = 'DELETE  FROM Message'.
        '         WHERE id = '.$intMessageId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteMessage '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteCriterion($intCriterionId) {
    $s = 'DELETE  FROM Criterion'.
        '         WHERE id = '.$intCriterionId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteCriterion '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteTopic($intTopicId) {
    $s = 'DELETE  FROM Topic'.
        '         WHERE id = '.$intTopicId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteTopic '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteIsAboutTopic($intPaperId, $intTopicId) {
    $s = 'DELETE  FROM IsAboutTopic'.
        '         WHERE paper_id = '.$intPaperId.
        '         WHERE topic_id = '.$intTopicId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deletePrefersTopic '.$this->mySql->getLastError());
  }

  /**
   */
  function deletePrefersTopic($intPersonId, $intTopicId) {
    $s = 'DELETE  FROM PrefersTopic'.
        '         WHERE person_id = '.$intPersonId.
        '         WHERE topic_id = '.$intTopicId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deletePrefersTopic '.$this->mySql->getLastError());
  }

  /**
   */
  function deletePrefersPaper($intPersonId, $intPaperId) {
    $s = 'DELETE  FROM PrefersPaper'.
        '         WHERE person_id = '.$intPersonId.
        '         WHERE paper_id = '.$intPaperId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deletePrefersPaper '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteDeniesPaper($intPersonId, $intPaperId) {
    $s = 'DELETE  FROM DeniesPaper'.
        '         WHERE person_id = '.$intPersonId.
        '         WHERE paper_id = '.$intPaperId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteDeniesPaper '.$this->mySql->getLastError());
  }

  /**
   */
  function deleteExcludesPaper($intPersonId, $intPaperId) {
    $s = 'DELETE  FROM ExcludesPaper'.
        '         WHERE person_id = '.$intPersonId.
        '         WHERE paper_id = '.$intPaperId;
    echo('<br>SQL: '.$s.'<br>');
    $result = $this->mySql->delete($s);
    if (!empty($result)) {
      return $result;
    }
    return $this->error('deleteExcludesPaper '.$this->mySql->getLastError());
  }

}

?>