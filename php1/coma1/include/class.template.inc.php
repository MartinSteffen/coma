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
class Template extends ErrorHandling {

  /**#@+@access private*/
  /**@var string*/
  var $strTemplate='';
  /**@var string*/
  var $strOutput='';
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
    // generiet selber einen Fehler und gibt ihn weiter
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
    $strContents = @file_get_contents($strFilename);
    if (empty($strContents)) {
      return $this->error('readTemplate', "Could not read template $strFilename");
    }
    $this->strTemplate =& $strContents;
    return $this->success(true);
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
  function assign(&$strAssocs) {
    if(!is_array($strAssocs)) {
      return $this->error('assign', "$strAssocs is not an array");
    }
    $this->strAssocs = array_merge($this->strAssocs, $strAssocs);
    return $this->success(true);
  }

  /**
   * Parsen der Seite
   *
   * Die Methode uebernimmt das tatsaechliche Parsen des Templates.
   * Alle mit assign uebergebenen Ersetzungen werden durchgefuehrt.
   *
   * @return true Erfolg
   * @access public
   *
   */
  function parse() {
    $strKeys = array();
    $strValues = array();
    foreach ($this->strAssocs as $key => $value) {
      // $key bereinigen... //momentan nur ?
      $key = preg_replace('/\?/', '\?', $key);
      if (is_object($value)) {
        if (get_class($value) != get_class($this)) {
          error('parse', 'associated Value is Object but not of class Template');
        }
        // Tag durch geparsten Output ersetzen
        $strKeys[] = '/(?i){'.$key.'}/';
        $value->parse();
        $strValues[] = $value->getOutput();
      }
      elseif (is_array($value)) {
        foreach ($value as $val) {
          $strKeys[] = '/(?is){'.$key.$val.'(.*?)}/';
          $strValues[] = '\\1';
        }
      }
      else {
        // Tag durch Value ersetzen
        $strKeys[] = '/(?i){'.$key.'}/';
        $strValues[] = $value;
      }
    }
    // Alle nicht zugeordneten rauswerfen!
    $strKeys[] = '/(?is){.*?}/';
    $strValues[] = '';
    $this->strOutput = preg_replace($strKeys, $strValues, $this->strTemplate);
    return $this->success(true);
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
  function getOutput() {
    return $this->success($this->strOutput);
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
    global $_renderTime;
    if (isset($_renderTime)) {
      list($usec, $sec) = explode(" ", microtime());
      $renderTime = ((float)$usec + (float)$sec);
      $renderTime = $renderTime - $_renderTime;
      $this->strOutput = str_replace('/RenderTime/', $renderTime, $this->strOutput);
    }
    print($this->strOutput);
    return $this->success();
  }

} // end class Template

?>