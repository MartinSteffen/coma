<?php

require_once 'PHPUnit.php';

// Coma must be installed in ../coma1/
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
include('../coma1/include/header.inc.php');


class ComaExample extends PHPUnit_TestCase
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
    $this->assertTrue($myDBAccess->checkEmail('sae@me.de'));
  }

  function test_checkLogin() {
    	global $myDBAccess;
      $this->assertTrue($myDBAccess->checkLogin('sae@me.de','pw'));
  }

  function test_getAllConferences() {
    	global $myDBAccess;
      $this->assertEquals(1,sizeof($myDBAccess->getAllConferences()));
  }

  function test_getConferenceDetailed() {
      	global $myDBAccess;
        $this->assertEquals(array(),getConferenceDetailed(100));
  }

  function test_getCriterionsOfConference() {
      	global $myDBAccess;
        $this->assertEquals([],$myDBAccess->getCriterionsOfConference(100));
  }

  function test_getTopicsOfConference() {
      	global $myDBAccess;
        $this->assertEquals([],getTopicsOfConference(100));
  }

  function test_getPersonIdByEmail() {
      	global $myDBAccess;
        $this->assertEquals(101,$myDBAccess->getPersonIdByEmail('sae@me.de'));
  }

  function test_getPerson() {
      	global $myDBAccess;
        $this->assertEquals($myDBAccess->getPerson(200),$myDBAccess->getPerson(200, 1));
  }

  function test_getPersonAlgorithmic() {
      	global $myDBAccess;
        $this->assertFalse($myDBAccess->getPersonAlgorithmic(1,200));
  }

  function test_getPersonAlgorithmic2() {
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

  function test_getUsersOfConference() {
        	global $myDBAccess;
          $this->assertNotSame($myDBAccess->getUsersOfConference(1, 1),$myDBAccess->getUsersOfConference(1, 2));
  }

  function test_getPaperSimple() {
      	global $myDBAccess;
        $this->assertFalse($myDBAccess->getPaperSimple(0));
  }


}

?>
