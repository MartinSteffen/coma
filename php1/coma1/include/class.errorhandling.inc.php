<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse ErrorHandling
 *
 * @author Jan Waller <jwa@informatik.uni-kiel.de>
 * @author Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage core
 * @access public
 *
 */
class ErrorHandling {

  /**#@+
   * @access private
   */
  /**@var string */
  var $strError = '';
  /**@var bool */
  var $blnError = false;
  /**#@-*/

  /**
   * Erzeugt einen Fehler mit der angegebenen Fehlerbeschreibung.
   *
   * @param string $strError optionale Fehlerbeschreibung
   * @return bool immer <b>false</b>
   * @see getLastError()
   * @access protected
   */
  function error($strMethod, $strError, $strComment='') {
    $strComment = empty($strComment) ? '' : " ($strComment)";
    $this->strError = '['.get_class($this)."->$strMethod: $strError$strComment]";
    $this->blnError = true;
    return false;
  }
  
  /**
   * Gibt einen Rueckgabewert erfolgreich, d.h. ohne Fehler zurueck.
   * 
   * @param mixed $val Rueckgabewert
   * @return mixed Der Rueckgabewert
   * @see getLastError()
   * @access protected
   */
  function success($val=true) {
    $this->blnError = false;
    $this->strError = '';
    return $val;
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
    return $strError;
  }
  
  /** 
   * Liefert zurueck, ob ein Fehler erzeugt worden ist.
   *
   * @return bool Ist ein Fehler erzeugt worden?
   * @see error()
   * @see success()
   * @see getLastError()
   * @access public
   */  
  function failed() {
    return $this->blnError;
  }
} // End class ErrorHandling

?>