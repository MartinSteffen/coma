<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**
 * Klasse Criterion
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author  Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Criterion {

  var $intId;
  var $intConferenceId;
  var $strName;
  var $strDescription;
  var $intMaxValue;
  var $fltWeight; // Wertebereich [0,1]

  function Criterion($intId, $intConferenceId, $strName, $strDescription, $intMaxValue, $fltWeight) {
    $this->intId = $intId;
    $this->intConferenceId = $intConferenceId;
    $this->strName = $strName;
    $this->strDescription = $strDescription;
    $this->intMaxValue = $intMaxValue;
    $this->fltWeight = $fltWeight;
  }

} // end class Criterion

?>