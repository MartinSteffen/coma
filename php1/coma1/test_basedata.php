<?php
/**
* @version $Id: test_basedata.php 2708 2005-01-21 17:35:21Z waller $
* @package coma1
* @subpackage test
* @author Oliver Niemann
*/
/***/

/**@ignore */
define('IN_COMA1', true);
/**@ignore */
define('NEED_NO_LOGIN', true);
require_once('include/header.inc.php');

$conferences = 1;
$persons = 1000;
$criterions = 3;
$papers = $persons/2-$persons/10;
$topics = 10;

echo '.';
$i = 0;
while ($i < $conferences) {
/* */echo 'C<br>';
  $myDBAccess->addConference('Conference'.$i, 'Homepage'.$i, 'Description'.$i,
    '2001-01-01', '2001-01-01', '2001-01-01', '2001-01-01',
    '2001-01-01', '2001-01-01', '2001-01-01',2,3,10,1000,0.5,1,1,1,2);
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
/* */echo 'P<br>';
  $myDBAccess->addPerson('Myname'.$i, 'Surname'.$i, $i.'email@mail.de', 'Title'.$i,
    'Affiliation'.$i, 'Street'.$i, 'City'.$i, 'Code'.$i,
    'State'.$i, 'Country'.$i, 'Phone'.$i, 'Fax'.$i,
    'pw');
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;
}

/* */echo 'Sandro Spezialuser<br>';
$myDBAccess->addPerson('Sandro', 'Surname', 'sae@me.de', 'The wise',
    'Not affiliated', 'Sesamestreet', 'Kiel', '24116',
    'SH', 'Germany', '1234567', '0910',
    'pw');
if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}

echo 'Sandro Spezialrechte<br>';
$i = 0;
while($i < $conferences){
$j = 2;
while($j < 6){
$myDBAccess->addRole($persons+1, $j, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
$j++;
}
$i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;

  while($j < $persons/10) {
  /* */echo 'R1<br>';
    $myDBAccess->addRole($j+1, 2, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $j = 0;
  while($j < $persons/3.33) {
  /* */echo 'R2<br>';
    $myDBAccess->addRole($j+1, 3, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $j = $persons/10;
  while($j < $persons/2) {
  /* */echo 'R3<br>';
    $myDBAccess->addRole($j+1, 4, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $j = $persons/3;
  while($j < $persons) {
  /* */echo 'R4<br>';
      $myDBAccess->addRole($j+1, 5, $i+1);
      if ($myDBAccess->failed()) {
	    echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;
  while ($j < $topics) {
  /* */echo 'T<br>';
    $myDBAccess->addTopic($i+1, 'Name'.$i.$j);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = $persons/10;
  while ($j < $persons/2) {
  /* */echo 'Pa<br>';
    $myDBAccess->addPaper($i+1, $j+1, 'Title'.$i.$j, 'Abstract'.$i.$j,
      array(), array(rand(1,$topics/2),rand($topics/2+1,$topics)));
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

$myDBAccess->addCoAuthor(1, 1);
      if ($myDBAccess->failed()) {
	    echo('Fehler: '.$myDBAccess->getLastError());
}
$myDBAccess->addCoAuthorName(2, 'Co Author Name');
      if ($myDBAccess->failed()) {
	    echo('Fehler: '.$myDBAccess->getLastError());
}

$j = 0;
$reports = 0;
while ($j < $papers) {
  $i = 0;

  while ($i < $persons/3) {
  if(rand(1,3) < 3) {
  /* */echo 'Rep'.$j.$i.'<br>';
    $myDBAccess->addReviewReport($j+1, $i+1, 'Summary'.$i.$j,
      'Remarks'.$i.$j, 'Confidential'.$i.$j);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
 $reports++;
}
    $i++;
  }
  $j++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;
  $totalWeight = 0;
  while ($j < $criterions) {
  /* */echo 'Cr<br>';
    if ($j < $criterions-1)
      $weight = rand(0,100-$totalWeight);
    else
      $weight = 100-$totalWeight;
    $myDBAccess->addCriterion($i+1, 'Name'.$i.$j, 'Description'.$i.$j, rand(6,10), $weight/100);
    $totalWeight += $weight;
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $reports) {
$j = 0;
  while($j < $criterions) {
  /* */echo 'Ra'.$i.$j.'<br>';
    $myDBAccess->addRating($i+1, $j+1, rand(1,6), 'Comment'.$i.$j);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
	  }
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 1;
  while ($j < 4) {
  /* */echo 'F<br>';
    $myDBAccess->addForum($i+1, 'title'.$i.$j, $j,0);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences * 3) {
/* */echo 'M<br>';
  $myDBAccess->addMessage('Subject'.$i, 'Text'.$i, 1, $i+1,0);
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
/* */echo 'PT<br>';
  for ($k = 0; $k < min(3, $topics-1); $k++) {
    if (rand(0,100) < 50+ ($k==0?40:0)) {
    $myDBAccess->addPrefersTopic($i+1, rand(1,$topics-1));
      if ($myDBAccess->failed()) {
          echo('Fehler: '.$myDBAccess->getLastError().' ************** Bei Duplicate entry unkritisch');
      }
    }
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
/* */echo 'PP<br>';
  for ($k = 0; $k < min(5, $papers-1); $k++) {
    if (rand(0,100) < 50+ ($k==0?40:0)) {
      $myDBAccess->addPrefersPaper($i+1, rand(1,$papers-1));
      if ($myDBAccess->failed()) {
          echo('Fehler: '.$myDBAccess->getLastError().' ************** Bei Duplicate entry unkritisch');
      }
    }
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
/* */echo 'DP<br>';
  for ($k = 0; $k < min(10, $papers-1); $k++) {
    if (rand(0,100) < 50+ ($k==0?40:0)) {
      $myDBAccess->addDeniesPaper($i+1, rand(1,$papers-2));
      if ($myDBAccess->failed()) {
          echo('Fehler: '.$myDBAccess->getLastError().' ************** Bei Duplicate entry unkritisch');
      }
    }
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
/* */echo 'EP<br>';
  for ($k = 0; $k < min(10, $papers-1); $k++) {
    if (rand(0,100) < 50+ ($k==0?40:0)) {
      $myDBAccess->addExcludesPaper($i+1, rand(2,$papers-1));
      if ($myDBAccess->failed()) {
          echo('Fehler: '.$myDBAccess->getLastError().' ************** Bei Duplicate entry unkritisch');
      }
    }
  }
  $i++;
}

?>