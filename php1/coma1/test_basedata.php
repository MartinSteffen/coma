<?php
/**
* @version $Id$
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
$papers = $persons;
$reports = $persons * $papers;
$topics = 10;

echo '.';
$i = 0;
while ($i < $conferences) {
  $myDBAccess->addConference('Conference'.$i, 'Homepage'.$i, 'Description'.$i,
    'AbstractDeadline', 'PaperDeadline', 'ReviewDeadline', 'FinalDeadline',
    'Notification', 'ConferenceStart', 'ConferenceEnd',2,3,10,1000,0.5,1,1,1,2,'Topics'.$i,
    'Criterions'.$i,'CritDesc'.$i,rand(1,10),0.5);
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
  $myDBAccess->addPerson('Myname'.$i, 'Surname'.$i, $i.'email@mail.de', 'Title'.$i,
    'Affiliation'.$i, 'Street'.$i, 'City'.$i, 'Code'.$i,
    'State'.$i, 'Country'.$i, 'Phone'.$i, 'Fax'.$i,
    '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;
  while($j < $persons/10) {
    $myDBAccess->addRole($j+1, 2, $i+1);
    $j++;
  }
  $j = 0;
  while($j < $persons/3) {
    $myDBAccess->addRole($j+1, 3, $i+1);
    $j++;
  }
  $j = $persons/10;
  while($j < $persons/2) {
    $myDBAccess->addRole($j+1, 4, $i+1);
    $j++;
  }
  $j = $persons/3;
  while($j < $persons) {
    $myDBAccess->addRole($j+1, 5, $i+1);
    $j++;
  }
  $i++;
}

$myDBAccess->addCoAuthor(1, 1);
$myDBAccess->addCoAuthorName(2, 'Co Author Name');

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = $persons/10;
  while ($j < $persons/2) {
    $myDBAccess->addPaper($i+1, $j+1, 'Title'.$i.$j, 'Abstract'.$i.$j,
      'Filepath'.$i.$j, 'Mime'.$i.$j, '');
    $j++;
  }
  $i++;
}

echo '.';
$j = 0;
while ($j < $papers) {
  $i = 0;
  while ($i < $persons/3) {
    if(rand(1,3) < 3) {
      $myDBAccess->addReviewReport($j+1, $i+1, 'Summary'.$i.$j,
        'Remarks'.$i.$j, 'Confidential'.$i.$j);
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
    $myDBAccess->addRating($i+1, $j+1, rand(1,6), 'Comment'.$i.$j);
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 1;
  while ($j < 4) {
    $myDBAccess->addForum($i+1, 'title'.$i.$j, $j,0);
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences * 3) {
  $myDBAccess->addMessage('Subject'.$i, 'Text'.$i, 1, $i+1,0);
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;
  while ($j < $criterions) {
    $myDBAccess->addCriterion($i+1, 'Name'.$i.$j, 'Description'.$i.$j, rand(6,10),rand(0,100) );
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $conferences) {
  $j = 0;
  while ($j < $topics) {
    $myDBAccess->addTopic($i+1, 'Name'.$i.$j);
    $j++;
  }
  $i++;
}

echo '.';
$i = 0;
while ($i < $topics) {
  $j = $papers/$topics*$i;
  while ($j < $papers/$topics*($i+1)) {
    $myDBAccess->addIsAboutTopic($j+1, $i+1);
    $j++;
    $i++;
  }
}

echo '.';
$i = 0;
while ($i < $persons) {
  $myDBAccess->addPrefersTopic($i+1, rand(1,$topics-1));
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
  $myDBAccess->addPrefersPaper($i+1, rand(1,$papers-1));
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
  $myDBAccess->addDeniesPaper($i+1, rand(1,$papers-2));
  $i++;
}

echo '.';
$i = 0;
while ($i < $persons) {
  $myDBAccess->addExcludesPaper($i+1, rand(2,$papers-1));
  $i++;
}

?>