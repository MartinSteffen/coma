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
 * Klasse Template
 *
 * Klasse zum Parsen der Templates
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Parser
 * @access public
 * 
 */
class Template {
  
  /**#@+@access private*/
  /**@var string*/
  var $strTemplate='';
  /**@var string*/
  var $strOutput='';
  /**@var string*/
  var $strError = '';
  /**@var array*/
  var $strAssocs = array();
  /**#@-*/
  
  /**
   * Konstruktor
   *
   * Der Konstruktor liest eine Template Datei ein.
   * 
   * @param string $strFilename Der Dateiname des Templates
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   *
   */
  function Template($strFilename) {
    return $this->readTemplate($strFilename);
  }

  /**
   * Neues Template
   *
   * Die Methode liest ein (neues) Template ein.
   * Sollte nicht verwendet werdem, stattdessen besser neues Objekt erzeugen!
   * 
   * @param string $strFilename Der Dateiname des Templates
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   * @access protected
   *
   */
  function readTemplate($strFilename) {
    $strContents = file_get_contents($strFilename);
    if (empty($strContents)) {
      return $this->error("Could not read Template [$strFilename]");
    }
    $this->strTemplate = $strContents;
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
   * @param array $strAssocs Das Array mit Zuweisungen
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function assign($strAssocs) {
    if(!is_array($strAssocs)) {
      return $this->error('Not an Array');
    }
    $this->strAssocs = array_merge($this->strAssocs, $strAssocs);
    return true;
  }
  
  /**
   * Parsen der Seite
   *
   * Die Methode uebernimmt das tatsächliche Parsen des Templates.
   * Alle mit assign uebergebenen Ersetzungenw erden durchgefuehrt.
   * 
   * @return true Erfolg
   * @access public
   *
   */
  function parse() {
    $strKeys = array_keys($this->strAssocs);
    $strKeys = array_map(create_function('$s', 'return "{".$s."}";'), $strKeys);
    $this->strOutput = eregi_replace($strKeys, array_values($this->strAssocs), $this->strTemplate);
    print_r($strKeys);
    return true;
  }
  
  /**
   * Ausgabe
   *
   * Die Methode gibt ein (geparstet) Template aus.
   * 
   * @return true Erfolg
   * @access public
   *
   */
  function output() {
    print($this->strOutput);
    return true;
  }
  
  /**
   * Fehler erzeugen
   *
   * Die Funktion <b>error()</b> erzeugt und speichert einen Fehlerstring.
   * 
   * @param string $strError Optionale Angabe einer Fehlerursache
   * @return false Es wird immer <b>false</b> zurueck gegeben
   * @see getLastError()
   * @access protected
   *
   */
  function error($strError='') {
    $this->strError = $strError;
    return false;
  }

  /**
   * Letzten Fehler ueberpruefen
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
    $strError = $this->strError;
    $this->strError = '';
    return $strError;
  }

} // end class Template

?>