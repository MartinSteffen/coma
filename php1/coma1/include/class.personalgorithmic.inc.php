<?php
/**
 * @version $Id
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
require_once(INCPATH.'class.person.inc.php');

/**
 * Klasse PersonAlgorithmic
 *
 * @author  Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class PersonAlgorithmic extends Person {

  var $objPreferredTopics;
  var $objPreferredPapers;
  var $objDeniedPapers;
  var $objExcludedPapers;

  function PersonAlgorithmic($intId, $strFirstname, $strLastname, $strEmail, $intRole,
                             $strTitle, $objPreferredTopics=false, $objPreferredPapers=false,
                             $objDeniedPapers=false, $objExcludedPapers=false) {
    $this->Person($intId, $strFirstname, $strLastname, $strEmail, $intRole, $strTitle);
    $this->objPreferredTopics = $objPreferredTopics;
    $this->objPreferredPapers = $objPreferredPapers;
    $this->objDeniedPapers = $objDeniedPapers;
    $this->objExcludedPapers = $objExcludedPapers;
  }

}
?>