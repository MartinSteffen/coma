<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../coma1/
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
include('../coma1/include/header.inc.php');


class dbAccessTest extends PHPUnit_TestCase
{

  function testTrue() {
      $this->assertTrue(TRUE);
  }

  function test_myDBAccess() {
  	global $myDBAccess;
    $this->assertFalse($myDBAccess->failed());
  }

  function test_checkEmail() {
  	global $myDBAccess;
    $this->assertTrue($myDBAccess->checkEmail('gub75@gmx.de'));
  }

  function test_checkLogin() {
    	global $myDBAccess;
    	$pw = sha1('gctgct');
      $this->assertTrue($myDBAccess->checkLogin('gub75@gmx.de',$pw));
  }

  function test_getAllConferences() {
    	global $myDBAccess;
      $this->assertEquals(4,sizeof($myDBAccess->getAllConferences()));
  }

  function test_getConferenceDetailed() {
      	global $myDBAccess;
        $this->assertFalse($myDBAccess->getConferenceDetailed(100));
  }

  function test_getCriterionsOfConference() {
      	global $myDBAccess;
        $this->assertEquals(array(),$myDBAccess->getCriterionsOfConference(100));
  }

  function test_getTopicsOfConference() {
      	global $myDBAccess;
        $this->assertEquals(array(),$myDBAccess->getTopicsOfConference(100));
  }

  function test_getPersonIdByEmail() {
      	global $myDBAccess;
        $this->assertEquals(1,$myDBAccess->getPersonIdByEmail('gub75@gmx.de'));
  }

  function test_getPerson() {
      	global $myDBAccess;
        $this->assertEquals($myDBAccess->getPerson(200),$myDBAccess->getPerson(200, 1));
  }

  function test_getPersonAlgorithmic() {
        	global $myDBAccess;
          $this->assertFalse($myDBAccess->getPersonAlgorithmic(200,1));
  }

  function getPersonDetailed() {
      	global $myDBAccess;
        $this->assertEquals($myDBAccess->getPersonDetailed(200),$myDBAccess->getPersonDetailed(200, 1));
  }

  function test_getUsersOfConference() {
      	global $myDBAccess;
        $this->assertEquals($myDBAccess->getUsersOfConference(200, 2),$myDBAccess->getUsersOfConference(200, 1));
  }

  function test_getPaperSimple() {
      	global $myDBAccess;
        $this->assertFalse($myDBAccess->getPaperSimple(0));
  }

  function test_getPaperFile() {
        	global $myDBAccess;
          $this->assertFalse($myDBAccess->getPaperFile(0));
  }

  function test_getPaperFile2($intPaperId) {
        	global $myDBAccess;
          $this->assertTrue($myDBAccess->getPaperFile(1));
  }

  function test_hasRoleInConference() {
          	global $myDBAccess;
            $this->assertTrue($myDBAccess->hasRoleInConference(1, 1, 2));
  }


}

?>
