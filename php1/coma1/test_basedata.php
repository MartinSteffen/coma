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
$persons = 100;
$criterions = 3;
$papers = $persons/2-$persons/10;
$reports = $persons/3 * $papers;
$topics = 10;

echo '.';
$i = 0;
while ($i < $conferences) {
echo 'C<br>';
  $myDBAccess->addConference('Conference'.$i, 'Homepage'.$i, 'Description'.$i,
    'AbstractDeadline', 'PaperDeadline', 'ReviewDeadline', 'FinalDeadline',
    'Notification', 'ConferenceStart', 'ConferenceEnd',2,3,10,1000,0.5,1,1,1,2,'Topics'.$i,
    'Criterions'.$i,'CritDesc'.$i,rand(1,10),0.5);
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
echo 'P<br>';
  $myDBAccess->addPerson('Myname'.$i, 'Surname'.$i, $i.'email@mail.de', 'Title'.$i,
    'Affiliation'.$i, 'Street'.$i, 'City'.$i, 'Code'.$i,
    'State'.$i, 'Country'.$i, 'Phone'.$i, 'Fax'.$i,
    '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;

  while($j < $persons/10) {
  echo 'R1<br>';
    $myDBAccess->addRole($j+1, 2, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $j = 0;
  while($j < $persons/3) {
  echo 'R2<br>';
    $myDBAccess->addRole($j+1, 3, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $j = $persons/10;
  while($j < $persons/2) {
  echo 'R3<br>';
    $myDBAccess->addRole($j+1, 4, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $j = $persons/3;
  while($j < $persons) {
  echo 'R4<br>';
      $myDBAccess->addRole($j+1, 5, $i+1);
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

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = $persons/10;
  while ($j < $persons/2) {
  echo 'Pa<br>';
    $myDBAccess->addPaper($i+1, $j+1, 'Title'.$i.$j, 'Abstract'.$i.$j,
      'Filepath'.$i.$j, 'Mime'.$i.$j, '');
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

$j = 0;
while ($j < $papers) {
  $i = 0;

  while ($i < $persons/3) {
  if(rand(1,3) < 3) {
  echo 'Rep'.$j.$i.'<br>';
    $myDBAccess->addReviewReport($j+1, $i+1, 'Summary'.$i.$j,
      'Remarks'.$i.$j, 'Confidential'.$i.$j);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
}
    $i++;
  }
  $j++;
}

echo '.';
$i = 0;
while ($i < $reports) {
$j = 0;
  while($j < $criterions) {
  echo 'Ra<br>';
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
  echo 'F<br>';
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
echo 'M<br>';
  $myDBAccess->addMessage('Subject'.$i, 'Text'.$i, 1, $i+1,0);
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;
  while ($j < $criterions) {
  echo 'Cr<br>';
    $myDBAccess->addCriterion($i+1, 'Name'.$i.$j, 'Description'.$i.$j, rand(6,10),rand(0,100) );
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
  echo 'T<br>';
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
while ($i < $topics) {
  $j = $papers/$topics*$i;
  while ($j < $papers/$topics*($i+1)) {
  echo 'AT<br>';
    $myDBAccess->addIsAboutTopic($j+1, $i+1);
    if ($myDBAccess->failed()) {
	  echo('Fehler: '.$myDBAccess->getLastError());
}
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
echo 'PT<br>';
  $myDBAccess->addPrefersTopic($i+1, rand(1,$topics-1));
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;

}

echo '.';
$i = 0;
while ($i < $persons) {
echo 'PP<br>';
  $myDBAccess->addPrefersPaper($i+1, rand(1,$papers-1));
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;

}

echo '.';
$i = 0;
while ($i < $persons) {
echo 'DP<br>';
  $myDBAccess->addDeniesPaper($i+1, rand(1,$papers-2));
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;

}

echo '.';
$i = 0;
while ($i < $persons) {
echo 'EP<br>';
  $myDBAccess->addExcludesPaper($i+1, rand(2,$papers-1));
  if ($myDBAccess->failed()) {
    echo('Fehler: '.$myDBAccess->getLastError());
}
  $i++;

}

?>