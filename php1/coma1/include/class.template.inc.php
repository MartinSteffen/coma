<?php
/**
 * @version $Id$
 * @package coma1
 */

if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse Template
 *
 * Klasse zum Parsen der Templates
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Jan Waller
 * @package coma1
 * @subpackage Parser
 * @access public
 * 
 */
class Template {
  
  /**#@+@access private*/
  /**@var string*/
  var $template='';
  /**@var string*/
  var $errString = '';
  /**@var array*/
  var $assocArray = array();
  /**#@-*/
  
  /**
   * Konstruktor
   *
   * Der Konstruktor liest eine Template Datei ein.
   * 
   * @param string $filename Der Dateiname des Templates
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   *
   */
  function Template($filename) {
    return $this->readTemplate($filename);
  }

  /**
   * Neues Template
   *
   * Die Methode liest ein (neues) Template ein.
   * Sollte nicht verwendet werdem, stattdessen besser neues Object erzeugen!
   * 
   * @param string $filenmae Der Dateiname des Templates
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   * @access protected
   *
   */
  function readTemplate($filename) {
    $contents = file_get_contents($filename);
    if (empty($contents)) {
      return $this->error("Could not read Template [$filename]");
    }
    $this->template = $contents;
    return true;
  }
  
  /**
   * Zuweisung von Werten auf Tags
   *
   * Die Methode erlaubt eine Zuweisung von Werten auf verschieden Tags.
   * Werden dabei Tags uebergeben, die bereits eine Zuweisung haben, so werden
   * die alten ueberschrieben.
   * Als Eingabe erwartet die Methode ein Array der Form:
   * <code>
   *  "key" => "wert"
   * </code>
   * Hierbei wird das Tag {key} durch den Wert "wert" ersetzt.
   * 
   * @param array $assocArray Das Array mit Zuweisungen
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function assign($assocArray) {
    if(!is_array($assocArray)) {
      return $this->error('Not an Array');
    }
    $this->assocArray = array_merge($this->assocArray, $assocArray);
    return true
  }
  
  /**
   * Parsen der Seite
   *
   * Die Methode uebernimmt das tatsächliche Parsen des Templates.
   * Alle mit assign uebergebenen Ersetzungenw erden durchgefuehrt.
   * 
   * @return string Das Template mit ersetzen Tags
   * @access public
   *
   */
  function parse() {
    $template = $this->template;
    $assocArray = $this->assocArray;
    $keyArray = array_keys($assocArray);
    $keyArray = array_map(create_function('$s', 'return "<{" . $s . "}>";'), $keyArray);
    $template = preg_replace($keyArray, array_values($assocArray), $template);
    return $template;
  }
  
  /**
   * error()
   *
   * Die Funktion <b>error()</b> speichert Fehler diese.
   * 
   * @param string $text Eine optionale Angabe einer Fehlerursache
   * @return false Es wird immer <b>false</b> zurueck gegeben
   * @see getLastError()
   * @access protected
   *
   */
  function error($text='') {
    $this->errString = $text;
    return false;
  }

  /**
   * getLastError()
   *
   * Die Funktion <b>getLastError()</b> gibt die letzte mit error
   * gesicherte Fehlermeldung zurueck und loescht diese aus dem Speicher.
   * 
   * @return string Die letzte Fehlermeldung
   * @see error()
   * @access public
   *
   */
  function getLastError() {
    $errString = $this->errString;
    $this->errString = '';
    return $errString;
  }

} // end class Template

?>