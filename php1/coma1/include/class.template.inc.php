<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

//  BACKWARDS COMPATIBILITY WITH PHP VERSIONS < 4.2.0
//  BC for file_get_contents()
if (!function_exists('file_get_contents')) {
  function file_get_contents($filename)
  {
    $fd = fopen($filename, 'rb');
    $content = fread($fd, filesize($filename));
    fclose($fd);
    return $content;
  }
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
   * Alle mit assign uebergebenen Ersetzungenw erden durchgefuehrt.
   *
   * @return true Erfolg
   * @access public
   * @todo Check ob gueltiges Objekt!!!
   * @todo Liste von Assoziationen Baum herunter schleppen
   *
   */
  function parse() {
    $strKeys = array();
    $strValues = array();
    foreach ($this->strAssocs as $key => $value) {
      if (is_object($value)) { // @todo Check ob g�ltiges Objekt!!!
        // Tag durch geparsten Output ersetzen
        $strKeys[] = '/(?i){'.$key.'}/';
        $value->parse();
        $strValues[] = $value->getOutput();
      }
      elseif (is_array($value)) {
        // IF BLOCK
        if ($key == 'if') {
          foreach ($value as $val) {
            $strKeys[] = '/(?is){'.$key.$val.'(.*?)}/';
            $strValues[] = '\\1';
          }
        }
        // REPEAT BLOCK
        else {
          $strKeys[] = '/(?is){'.$key.'(.*?)}/';
          $strVal = '';
          foreach ($values as $val) {
            $strVal .= '\\1';
          }
          $strValues[] = $strVal;
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
    print($this->strOutput);
    return $this->success();
  }

} // end class Template

?>