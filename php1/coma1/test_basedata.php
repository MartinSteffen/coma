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



$i = 0;

while ($i < $conferences) {
	$myDBAccess->addConference('Conference'   .$i,
	                           'Homepage'     .$i,
	                           'Description'  .$i,
	                           'AbstractDeadline',
                             'PaperDeadline'   , 
                             'ReviewDeadline'  , 
                             'FinalDeadline'   ,
                             'Notification'    , 
                             'ConferenceStart' , 
                             'ConferenceEnd'
                            );
    $i++;
    }



 $i = 0;

 while ($i < $persons) {
 	$myDBAccess->addPerson('Myname'.$i, 'Surname'.$i, $i.'email@mail.de', 'Title'.$i,
                     'Affiliation'.$i, 'Street'.$i, 'City'.$i, 'Code'.$i,
                     'State'.$i, 'Country'.$i, 'Phone'.$i, 'Fax'.$i,
                     '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
     $i++;
     }




$i = 0;

while ($i < $conferences) {
$j = 0;
	while($j < $persons){
	$k = 2;
	while($k < 6)
		$myDBAccess->addRole($j+1, $k, $i+1);
		$k++;
		}
    	$j++;
    }
    $i++;
}


$myDBAccess->addCoAuthor(1, 1);
$myDBAccess->addCoAuthorName(2, 'Co Author Name')

$i = 0;

while ($i < $conferences) {
$j = 0;
while ($j < $persons) {
	$myDBAccess->addPaper($i+1, $j+1, 'Title'.$i.$j, 'Abstract'.$i.$j,
                    'Filepath'.$i.$j, 'Mime'.$i.$j, '');
    $j++;
}
    $i++;
}


$j = 0;
while ($j < $persons) {
$i = 0;
while ($i < $papers) {

	$myDBAccess->addReviewReport($i+1, $j+1, 'Summary'.$i.$j,
                           'Remarks'.$i.$j, 'Confidential'.$i.$j);
    $i++;
    }
    $j++;
}

$i = 0;

while ($i < $reports) {
$j = 0;
while($j < $criterions) {
	$myDBAccess->addRating($i+1, $j+1, rand(1,6), 'Comment'.$i.$j);
    $j++;
    }
    $i++;
    }



$i = 0;

while ($i < $conferences) {
$j = 0;
while ($j < 3) {
	$myDBAccess->addForum($i+1, 'title'.$i.$j, $j+1,0);
    $j++;
    }
    $i++;
    }


$i = 0;

while ($i < $conferences * 3) {

	$myDBAccess->addMessage('Subject'.$i, 'Text'.$i, 1, $i+1,0);

    $i++;
    }


$i = 0;

while ($i < $conferences) {
$j = 0;
while ($j < $criterions) {

	$myDBAccess->addCriterion($i+1, 'Name'.$i.$j, 'Description'.$i.$j, rand(6,10),rand(0,100) );

    $j++;
    }
    $i++;
    }



$i = 0;

while ($i < $conferences) {
$j = 0;
while ($j < $topics) {
	$myDBAccess->addTopic($i+1, 'Name'.$i.$j);
    $j++;
    }
    $i++;
    }



$i = 0;

while ($i < $papers) {
$j = 0;
while ($j < $topics) {
	$myDBAccess->addIsAboutTopic($i+1, $j+1);
    $j++;
    $i++;
    }
    }


$i = 0;

while ($i < $persons) {
$j = 0;
while ($j < $topics) {
	$myDBAccess->addPrefersTopic($i+1, $j+1);
    $j++;
    $i++;
    }
    }


$i = 0;

while ($i < $persons) {
$j = 0;
while ($j < $papers) {
	$myDBAccess->addPrefersPaper($i+1, $j+1);
    $j++;
    $i++;
    }
    }


$i = 0;

while ($i < $persons) {
$j = 0;
while ($j < $papers) {
	$myDBAccess->addDeniesPaper($i+1, $j+1);
    $j++;
    $i++;
    }
    }


$i = 0;

while ($i < $persons) {
$j = 0;
while ($j < $papers) {
	$myDBAccess->addExcludesPaper($i+1, $j+1);
    $j++;
    $i++;
    }
    }

?>