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
require_once(INCPATH.'class.papersimple.inc.php');

/**
 * Klasse PaperDetailed
 *
 * @author Jan Waller <jwa@informatik.uni-kiel.de>
 * @author Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PaperDetailed extends PaperSimple {

  var $intCoAuthorIds; // enthaelt ID's der Co-Autoren; NULL, falls nicht in der DB (dann nur Name)
  var $strCoAuthors;   // enthaelt die Namen der Co-Autoren korrespondierend zu $intCoAuthorIds;
                       // beide CoAuthor-Arrays muessen gleiche Laenge haben, da sie sich immer
                       // direkt aufeinander beziehen (intCoAuthorIds darf false-Eintraege haben)
  var $strAbstract;
  var $strMimeType;  
  var $intVersion;
  var $strFilePath;  

  function PaperDetailed($intId, $strTitle, $intAuthorId, $strAuthor, $intStatus,
                         $strLastEdit, $fltAvgRating, $intCoAuthorIds, $strCoAuthors,
                         $strAbstract, $strMimeType, $intVersion, $strFilePath, $objTopics) {
    $this->PaperSimple($intId, $strTitle, $intAuthorId, $strAuthor, $intStatus, $strLastEdit,
                       $fltAvgRating, $strFilePath, $objTopics);
    $this->intCoAuthorIds = $intCoAuthorIds;
    $this->strCoAuthors   = $strCoAuthors;
    $this->strAbstract    = $strAbstract;
    $this->strMimeType    = $strMimeType;
    $this->strLastEdit    = $strLastEdit;    
    $this->intVersion     = $intVersion;
  }

} // end class PaperDetailed

?>