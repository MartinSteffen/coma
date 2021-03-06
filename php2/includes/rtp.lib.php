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

function getPerson($personId) {

	return array("name" => "TODO");

}

function getReviewCountForPaper($paperId) {

	global $sql;

	if (!$paperId) {
		$paperId = "0";
	}

	$query="SELECT id FROM reviewreport WHERE paper_id=$paperId";

	$countArr=$sql->queryAssoc($query);

	$result=0;

	foreach($countArr as $reviewreport) {

		$query="SELECT COUNT(*) FROM rating WHERE review_id=" . $reviewreport['id'];
		$hasReviews=$sql->query($query);
		if ($hasReviews[0][0] > 0) {
			$result++;
		}

	}
	
	return($result);

}

function getReviewerCountForPaper($paperId) {

	global $sql;

	if (!$paperId) {
		$paperId = "0";
	}

	$query="SELECT count(*) FROM reviewreport WHERE paper_id=$paperId";

	$countArr=$sql->query($query);

	$result=$countArr[0][0];

	return($result);

}

function isReviewed($reviewId) {
	global $sql;

	if (!$reviewId) {
		$reviewId = "0";
	}

	$query="SELECT COUNT(*) FROM rating WHERE review_id=" . $reviewId;
	
	$countArr=$sql->query($query);

	return $countArr[0][0]>0;


}

function compareGrade($a, $b) {
	if ($a['total_grade'] < $b['total_grade']) return 1;
	else if ($a['total_grade'] > $b['total_grade']) return -1;
	else if ($a['state'] >= 3 && $b['state'] < 3) return -1;
	else if ($a['state'] < 3 && $b['state'] >= 3) return 1;
	else return 0;
}

function getAllRejectedPapersForConferenceSortByTotalScore( $conferenceId ) {

	global $sql;

	if (!$conferenceId) {
		$conferenceId="0";
	}

	$reviewQuery="SELECT min_reviews_per_paper FROM conference WHERE id=$conferenceId";
	
	$minReviewsArr=$sql->query($reviewQuery);

	$minReviews=$minReviewsArr[0][0];

	$graded = array();
	$ungraded = array();

	$queryStr="SELECT id FROM paper WHERE conference_id=$conferenceId AND state=4";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		$paperId=$row['id'];
		$paperArr=getPaper( $paperId );

		$reviewCount=getReviewCountForPaper( $paperId );

		$paperArr['min_reviews']=$minReviews;

		if ( $reviewCount==0 ) {
			$paperArr['total_grade'] = 0;
			$paperArr['review_count'] = 0;
			$paperArr['review_status'] = "notAtAll";
			$ungraded[ ] = $paperArr;
		} else {
			$totalGrade=getTotalGradeForPaper( $paperId );
			$totalGradeList=getTotalGradeListForPaper( $paperId );
			if ($reviewCount>=$minReviews) {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['totalgradelist'] = implode(", ",$totalGradeList);
				$paperArr['review_count'] = $reviewCount;
				$paperArr['review_status'] = "complete";
				$graded[ ] = $paperArr;
			} else {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['totalgradelist'] = implode(", ",$totalGradeList);
				$paperArr['review_count'] = $reviewCount;
				$paperArr['review_status'] = "partially";
				$graded[ ] = $paperArr;
			}
		}
	}

	usort($graded, "compareGrade");

	return array_merge ( $graded, $ungraded );

}

function getAllAcceptedPapersForConferenceSortByTotalScore( $conferenceId ) {

	global $sql;

	if (!$conferenceId) {
		$conferenceId="0";
	}

	$reviewQuery="SELECT min_reviews_per_paper FROM conference WHERE id=$conferenceId";
	
	$minReviewsArr=$sql->query($reviewQuery);

	$minReviews=$minReviewsArr[0][0];

	$graded = array();
	$ungraded = array();

	$queryStr="SELECT id FROM paper WHERE conference_id=$conferenceId AND state=3";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		$paperId=$row['id'];
		$paperArr=getPaper( $paperId );

		$reviewCount=getReviewCountForPaper( $paperId );

		$paperArr['min_reviews']=$minReviews;

		if ( $reviewCount==0 ) {
			$paperArr['total_grade'] = 0;
			$paperArr['review_count'] = 0;
			$paperArr['review_status'] = "notAtAll";
			$ungraded[ ] = $paperArr;
		} else {
			$totalGrade=getTotalGradeForPaper( $paperId );
			$totalGradeList=getTotalGradeListForPaper( $paperId );
			if ($reviewCount>=$minReviews) {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['totalgradelist'] = implode(", ",$totalGradeList);
				$paperArr['review_count'] = $reviewCount;
				$paperArr['review_status'] = "complete";
				$graded[ ] = $paperArr;
			} else {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['totalgradelist'] = implode(", ",$totalGradeList);
				$paperArr['review_count'] = $reviewCount;
				$paperArr['review_status'] = "partially";
				$graded[ ] = $paperArr;
			}
		}
	}

	usort($graded, "compareGrade");

	return array_merge ( $graded, $ungraded );

}


function getAllPapersForConferenceSortByTotalScore( $conferenceId) {

	global $sql;

	if (!$conferenceId) {
		$conferenceId="0";
	}

	$reviewQuery="SELECT min_reviews_per_paper FROM conference WHERE id=$conferenceId";
	
	$minReviewsArr=$sql->query($reviewQuery);

	$minReviews=$minReviewsArr[0][0];

	$graded = array();
	$ungraded = array();

	$queryStr="SELECT id FROM paper WHERE conference_id=$conferenceId";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		$paperId=$row['id'];
		$paperArr=getPaper( $paperId );

		$reviewCount=getReviewCountForPaper( $paperId );
		$reviewerCount=getReviewerCountForPaper( $paperId );

		$paperArr['min_reviews']=$minReviews;

		if ( $reviewCount==0 ) {
			$paperArr['total_grade'] = 0;
			$paperArr['review_count'] = 0;
			$paperArr['reviewer_count'] = $reviewerCount;
			$paperArr['review_status'] = "notAtAll";
			$ungraded[ ] = $paperArr;
		} else {
			$totalGrade=getTotalGradeForPaper( $paperId );
			$totalGradeList=getTotalGradeListForPaper( $paperId );
			if ($reviewCount>=$reviewerCount) {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['totalgradelist'] = implode(", ",$totalGradeList);
				$paperArr['review_count'] = $reviewCount;
				$paperArr['reviewer_count'] = $reviewerCount;
				$paperArr['review_status'] = "complete";
				$graded[ ] = $paperArr;
			} else {
				$paperArr['total_grade'] = number_format( $totalGrade*100, 2);
				$paperArr['totalgradelist'] = implode(", ",$totalGradeList);
				$paperArr['review_count'] = $reviewCount;
				$paperArr['reviewer_count'] = $reviewerCount;
				$paperArr['review_status'] = "partially";
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

	return $gradeSum / getReviewCountForPaper($paperId) ;

}

function getTotalGradeListForPaper($paperId) {

	global $sql;

	if (!$paperId) {
		$paperId="0";
	}

	$gradeList=array();

	$queryStr="SELECT id FROM reviewreport WHERE paper_id=$paperId";

	$arr=$sql->query($queryStr);

	foreach($arr as $row) {
		if (isReviewed($row['id'])) {
			$gradeList[] = number_format( getTotalGradeForReviewReport( $row['id'] ) * 100 , 2);
		}
	}

	if (count($arr)==0) {
		// no reviewReport done yet for this paper!
		return array();
	}

	return $gradeList ;

}


function getTotalGradeForReviewReport($reviewReportId) {

	global $sql;

	if (!$reviewReportId) {
		$reviewReportId="0";
	}

	$weightenedGrades = array();

	$queryStr="SELECT SUM(criterion.quality_rating) FROM reviewreport, paper, criterion
		     WHERE reviewreport.id = ".$reviewReportId."
		     AND reviewreport.paper_id = paper.id
		     AND paper.conference_id = criterion.conference_id";
	
	$sumArr=$sql->query($queryStr);
	$summary = $sumArr[0][0];

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

		$weightenedGrades[ $row['criterion_id'] ] = ( ($row['grade'] -1) / max(($row['max_value'] -1), 1) ) * ( $row['quality_rating'] / $summary);
	}


	$gradeSum=0;
	foreach($weightenedGrades as $grade) {
		$gradeSum += $grade;
	}

	return $gradeSum;

}

?>
