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
   * Prueft, ob die globalen User-Daten gueltig sind.
   *
   * @return bool <b>true</b> gdw. die Daten in der Person-Tabelle hinterlegt sind
   * @access public
   * @author Tom (15.12.04)
   */
  function checkLogin() {
    $s = 'SELECT  id, email, password'.
        ' FROM    Person'.
        ' WHERE   email = \''.$_SESSION['uname'].'\''.
        ' --AND     password = \''.$_SESSION['password'].'\'';
    $data = $this->mySql->select($s);
    if (!empty($data)) {
      return true;
    }
    return $this->error('checkLogin '.$this->mySql->getLastError());
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
   *   gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
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
    return $this->error('getPerson '.$this->mySql->getLastError());
  }

  /**
   * Liefert ein PersonDetailed-Objekt mit den Daten der Person $intPersonId.
   *
   * @param int $intPersonId ID der Person
   * @return PersonDetailed <b>false</b>, falls keine Person mit ID $intPersonId
   *   gefunden wurde
   * @access public
   * @author Sandro, Tom (03.12.04, 12.12.04)
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
      return (new PaperSimple($data[$i]['id'], $data[$i]['title'],
                $data[$i]['author_id'], $strAuthor, $data[$i]['state'], $fltAvgRating));     
    }
    return $this->error('getPaper '.$this->mySql->getLastError());
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
        if (empty($objAuthor)) {
          return $this->error('getPapersOfAuthor '.$this->mySql->getLastError());
        }
      	$strAuthor = $objAuthor->getName();
      	$objPapers[$i] = new PaperSimple($data[$i]['id'], $data[$i]['title'],
      	                   $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
      	                   $fltAvgRating);
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
      	$objAuthor = $this->getPerson($intReviewerId);
      	if (empty($objAuthor)) {
      	  return $this->error('getPapersOfReviewer '.$this->mySql->getLastError());
        }
      	$strAuthor = $objAuthor->getName();
      	$fltAvgRating = $this->getAverageRatingOfPaper($data[$i]['id']);
      	$objPapers[$i] = new PaperSimple($data[$i]['id'], $data[$i]['title'],
      	                   $data[$i]['author_id'], $strAuthor, $data[$i]['state'],
      	                   $fltAvgRating);
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
    return $this->error('getReviewersOfPaper '.$this->mySql->getLastError());    
  }

  /**
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
    return $this->error('getReviewersOfPaper '.$this->mySql->getLastError());    
  }

  /**
   */
  function getReview($intReviewId) {
    $s = 'SELECT  id, paper_id, reviewer_id'.
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
      return (new Review($data[0]['id'], $data[0]['paper_id'],
                $paper_data[0]['title'], $objAuthor->strEmail, $objAuthor->getName(),                
                getReviewRating($intReviewId), getAverageRatingOfPaper($paper_data[0]['id']),
                $objReviewer->strEmail, $objReviewer->getName()));
    }
    return $this->error('getReviewDetailed '.$this->mySql->getLastError());
  }

  /**
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
      $s = 'SELECT  grade, comment, name, description, max_value'.
          ' FROM    Rating r'.
          ' INNER   JOIN Criterion c'.
          ' ON      c.id  = r.criterion_id'.
          ' WHERE   review_id = '.$data[0]['id'];
      $rating_data = $this->mySql->select($s);
      $intRatings = array();
      $strComments = array();
      $intMaxValues = array();
      $strCriteria = array();
      $strDescriptions = array();
      if (!empty($rating_data)) {
      	for ($i = 0; $i < count($rating_data); $i++) {
      	  $intRatings[] = $rating_data[$i]['grade'];
      	  $intMaxValues[] = $rating_data[$i]['max_value'];
      	  $strComments[] = $rating_data[$i]['comment'];
      	  $strCriteria[] = $rating_data[$i]['name'];
      	  $strDescriptions[] = $rating_data[$i]['description'];
      	}
      }
      return (new ReviewDetailed($data[0]['id'], $data[0]['paper_id'],
                $paper_data[0]['title'], $objAuthor->strEmail, $objAuthor->getName(),                
                getReviewRating($intReviewId), getAverageRatingOfPaper($paper_data[0]['id']),
                $objReviewer->strEmail, $objReviewer->getName(),
                $data[0]['summary'], $data[0]['remarks'], $data[0]['confidential'],
                $intRatings, $strComments, $intMaxValues, $strCriteria, $strDescriptions));
    }
    return $this->error('getReviewDetailed '.$this->mySql->getLastError());
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
    return $this->error('getNextMessages '.$this->mySql->getLastError());;
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
    return $this->error('getThreadsOfForum '.$this->mySql->getLastError());;
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
    return $this->error('getAllForums '.$this->mySql->getLastError());;
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
    return $this->error('getForumOfPaper '.$this->mySql->getLastError());;
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
    return $this->error('getForumsOfUser '.$this->mySql->getLastError());;
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
    return $this->error('getForumDetailed '.$this->mySql->getLastError());
  }

}

?>