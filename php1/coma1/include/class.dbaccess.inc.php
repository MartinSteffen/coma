<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

// by Jan: verbesserter Include
if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}
require_once(INCPATH.'class.mysql.inc.php');
require_once(INCPATH.'class.person.inc.php');
require_once(INCPATH.'class.persondetailed.inc.php');
require_once(INCPATH.'class.paper.inc.php');
require_once(INCPATH.'class.papersimple.inc.php');
require_once(INCPATH.'class.paperdetailed.inc.php');
require_once(INCPATH.'class.message.inc.php');
require_once(INCPATH.'class.forum.inc.php');
require_once(INCPATH.'class.forumdetailed.inc.php');

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


  /**
   */
  function getAllConferences() {
    return false;
  }

  /**
   */
  function checkLogin() {
    return true;
  }

  /**
   */
  function getConferenceConfig() {
    return false;
  }

  /**
   * Liefert die ID der Person, deren E-Mail-Adresse $strEmail ist.
   *
   * @param string $strEmail E-Mail-Adresse der Person
   * @return int ID bzw. <b>false</b>, falls keine Person mit E-Mail-Adresse
   *   $strEmail gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04)
   */
  function getPersonIdByEmail($strEmail) {
    $s = 'SELECT  id'.
        ' FROM    Person'.
        ' WHERE   email = \''.$strEmail.'\'';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      return $data[0]['id'];
    }
    return false;
  }
  
  /**
   * Liefert ein Person-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @return Person <b>false</b>, falls keine Person mit ID $intPersonId
   *   gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04)
   */
  function getPerson($intPersonId) {
    $s = 'SELECT  id, email, first_name, last_name'.
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
      return (new Person($data[0]['id'], $data[0]['email'],
                $data[0]['first_name'], $data[0]['last_name'], $role_type));
    }
    return false;
  }

  /**
   * Liefert ein PersonDetailed-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @return PersonDetailed <b>false</b>, falls keine Person mit ID $intPersonId
   *   gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04)
   */
  function getPersonDetailed($intPersonId) {
    $s = 'SELECT  id, email, first_name, last_name, title, affiliation,'.
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
      return (new PersonDetailed($data[0]['id'], $data[0]['email'],
                $data[0]['first_name'], $data[0]['last_name'], $role_type,
                $data[0]['title'], $data[0]['affiliation'], $data[0]['street'],
                $data[0]['city'], $data[0]['postal_code'], $data[0]['state'],
                $data[0]['country'], $data[0]['phone_number'],
                $data[0]['fax_number']));
    }
    return false;
  }

  /**
   * Liefert ein Array von PaperSimple-Objekten des Autors $intAuthorId.
   *
   * @param int $intAuthorId ID des Autors
   * @return PaperSimple [] <b>false</b>, falls keine Paper des Autors
   *   $intAuthorId gefunden wurden
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
      	$strAuthor = $objAuthor->getName();
      	$objPapers[$i] = new PaperSimple($data[$i]['id'], $data[$i]['title'],
      	                   $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
      	                   $fltAvgRating);
      }
      return $objPapers;
    }
    return false;
  }
  

  /**
   * Liefert ein Array von PaperSimple-Objekten des Reviewers $intReviewerId.
   *
   * @param int $intReviewerId ID des Reviewers
   * @return PaperSimple [] <b>false</b>, falls keine Reviews des Reviewers
   *   $intReviewerId gefunden wurden
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
      	$objAuthor = $this->getPerson($intAuthorId);
      	$strAuthor = $objAuthor->getName();
      	$fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      	$objPapers[$i] = new PaperSimple($data[$i]['id'], $data[$i]['title'],
      	                   $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
      	                   $fltAvgRating);
      }
      return $objPapers;
    }
    return false;
  }

  /**
   * Liefert PaperDetailed-Objekt mit den Daten des Papers $intPaperId.
   *
   * @param int $intPaperId ID des Papers
   * @return PaperDetailed <b>false</b>, falls kein Paper mit der ID
   *   $intPaperId gefunden wurde
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
          $objCoAuthor = $this->getPerson($cadata[$i]['coauthor_id']);
          if (empty($objCoAuthor)) { // d.h. Co-Autor nicht im System => nimm Name aus Tabelle
            $intCoAuthorIds[$i] = NULL;
            $strCoAuthors[$i] = $cadata[$i]['name'];
          }
          else {
            $intCoAuthorIds[$i] = $cadata[$i]['coauthor_id'];
            $strCoAuthors[$i] = $objCoAuthor->getName();
          }
        }
      }
      return (new PaperDetailed($intPaperId, $data[0]['title'], $data[0]['author_id'],
                $strAuthor, $data[0]['state'], $fltAvgRating, $intCoAuthorIds,
                $strCoAuthors, $data[0]['abstract'], $data[0]['format'],
                $data[0]['last_edited'], $data[0]['filename']));
    }
    return $this->error('getPaperDetailed '.$this->mySql->getLastError());
  }

  /**
   * Liefert den Durchschnitt der Gesamtbewertungen des Papers $intPaperId.
   *
   * @param int $intPaperId ID des Papers
   * @return flt <b>false</b>, falls keine Bewertungen des Papers gefunden wurden.
   * @access private
   * @author Sandro, Tom (06.12.04)
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
    return false;
  }

  /**
   * Liefert die Gesamtbewertung eines Reviews (die Durchschnittsnote aller
   * Kriterien unter Beruecksichtigung der Gewichtungen).
   *
   * @param int $intReviewId ID des Reviews
   * @return int <b>false</b>, falls das Review nicht existiert
   * @access private
   * @author Sandro, Tom (06.12.04)
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
    return false;
  }

  /**
   */
  function getReviewsOfReviewer($intReviewerId) {
    return false;
  }

  /**
   */
  function getReviewersOfPaper($intPaperId) {
    return false;
  }

  /**
   */
  function getReviewsOfPaper($intPaperId) {
    return false;
  }

  /**
   */
  function getReviewDetailed($intReviewId) {
    return false;
  }

  /**
   */
  function checkAccessToForum($intForumId) {
    return false;
  }

  /**
   */
  function getNextMessages($intMessageId) {
    $s = 'SELECT  id, sender_id, send_time, subject, text'.
        ' FROM    Message'.
        ' WHERE   reply_to = \''.$intMessageId.'\'';
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
    return false;
  }
  
  /**
   */
  function getThreadsOfForum($intForumId) {
    $s = 'SELECT  id, sender_id, send_time, subject, text'.
        ' FROM    Message'.
        ' WHERE   forum_id = \''.$intForumId.'\''.
        ' AND     reply_to IS NULL';
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
    return false;
  }

  /**
   */
  function getAllForums() {
    $s = 'SELECT  id, title'.
        ' FROM    Forum'; //.
        //' WHERE   conference_id = \''.???.'\'';
    $data = $this->mySql->select($s);    
    if (!empty($data)) {            
      $forum = (new Forum($data[$i]['id'], $data[$i]['title'], 0, false));
      return $forum;
    }
    return false;
  }

  /**
   */
  function getForumOfPaper($intPaperId) {
    $s = 'SELECT  id, title'.
        ' FROM    Forum'.
        ' WHERE   paperId = '.$intPaperId; //.
        //' AND   conference_id = \''.???.'\'';
    $data = $this->mySql->select($s);    
    if (!empty($data)) {            
      $forum = (new Forum($data[$i]['id'], $data[$i]['title'], 0, false));
      return $forum;
    }
    return false;
  }

  /**
   */
  function getForumsOfUser($strUserId) {
    $userData = getPerson($strUserId);
    if (!empty($userData)) {
      $allForums = getAllForums();
      $forums = array();
      if (!empty($allForums)) {
    	for ($i = 0; $i < count($allForums); $i++) {
    	  if ($allForums[$i]->isUserAllowed($userData)) {
    	    $forums[] = $allForums[$i];
          }
        }    	  
      }
    }
    return false;
  }

  /**
   */
  function getForumDetailed($intForumId) {
    $s = 'SELECT  id, title'.
        ' FROM    Forum'.
        ' WHERE   id = \''.$intForumId.'\'';
    $data = $this->mySql->select($s);    
    if (!empty($data)) { 
      $forum = (new ForumDetailed($data[0]['id'], $data[0]['title'],
                  0, false, $this->getThreadsOfForum($intForumId)));
      return $forum;
    }
    return false;
  }

}

?>