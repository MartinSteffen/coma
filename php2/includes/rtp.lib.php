<?php

// This library defines several functions for the Rating-to-Program-Algorithm
//
// Responsibility for this file is at mje

// TODO:Doku
function getPaper($paperId) {

	global $sql;
	$returnArr=$sql->queryAssoc("SELECT * from paper where id=$paperId");
	$returnArr=$returnArr[0];
	return $returnArr;

}

// TODO:Doku

function isCompletelyReviewed($paperId) {

	// TODO: all
	return false;

}

function isNotReviewedAtAll($paperId) {

	// TODO: all
	return false;

}

function getAllPapersForConferenceSortByTotalScore( $conferenceId) {

	function compareGrade($a, $b) {
		if ($a['total_grade'] < $b['total_grade']) return 1;
		else if ($a['total_grade'] > $b['total_grade']) return -1;
		else return 0;
	}

	global $sql;

	if (!$conferenceId) {
		$conferenceId="0";
	}

	$graded = array();
	$ungraded = array();

	$queryStr="SELECT id FROM paper WHERE conference_id=$conferenceId";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		$paperId=$row['id'];
		$paperArr=getPaper( $paperId );

		if (isNotReviewedAtAll( $paperId )) {
			$paperArr['total_grade'] = 0;
			$paperArr['reviewStatus'] = "notAtAll";
			$ungraded[ ] = $paperArr;
		} else {
			$totalGrade=getTotalGradeForPaper( $paperId );
			if (isCompletelyReviewed( $paperId )) {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['reviewStatus'] = "complete";
				$graded[ ] = $paperArr;
			} else {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['reviewStatus'] = "partially";
				$graded[ ] = $paperArr;
			}
		}
	}

	usort($graded, "compareGrade");

	return array_merge ( $graded, $ungraded );

}

function getTotalGradeForPaper($paperId) {

	global $sql;

	if (!$paperId) {
		$paperId="0";
	}

	$gradeSum=0;

	$queryStr="SELECT id FROM reviewreport WHERE paper_id=$paperId";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		$gradeSum += getTotalGradeForReviewReport( $row['id'] );
	}

	if (count($arr)==0) {
		// no reviewReport done yet for this paper!
		return 0;
	}

	return $gradeSum / count ($arr) ;

}

function getTotalGradeForReviewReport($reviewReportId) {

	global $sql;

	if (!$reviewReportId) {
		$reviewReportId="0";
	}

	$result=array();

	$queryStr="SELECT r.grade, r.criterion_id, c.max_value, c.quality_rating 
					FROM rating r, criterion c 
					WHERE r.review_id=$reviewReportId AND r.criterion_id=c.id";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		
		if (!isset($row['quality_rating'])) {
			$row['quality_rating']=100;
		}
	
		if (!$row['max_value']) {
			$row['max_value']=10;
		}		

		$weightenedGrades[ $row['criterion_id'] ] = ( $row['grade'] / $row['max_value'] ) * ( $row['quality_rating'] / 100);
	}


	$gradeSum=0;
	foreach($weightenedGrades as $grade) {
		$gradeSum += $grade;
	}

	return $gradeSum;

}

?>
