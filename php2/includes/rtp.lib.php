<?php

// This library defines several functions for the Rating-to-Program-Algorithm
//
// Responsibility for this file is at mje

// TODO:Doku
function getPaper($paperId) {

	// ein Paper ist ein assoziatives Array, das sämtliche Eigenschaften eines Papers als Keys und ihre Werte als Values enthält. Bewertungen bilden evtl. Unter-Arrays in den entsprechenden keys.

	return array(0);

}

// TODO:Doku
function needsDiscussion($paperId) {

	// TODO: all
	return false;

}

// TODO:Doku

function isCompletelyReviewed($paperId) {

	// TODO: all
	return false;

}

function hasReviewed($personId, $paperId) {

	//TODO: all
	return false;

}

function getTotalScoreOfPaper($paperId) {

	return 0;

}

function getTotalScoresOfPaperAsArray($paperId) {

	return array(0);

}

function getTotalScoreDifferenceOfPaper($paperId) {

	// normierte Differenz der einzelnen Gesamtbenotungen zueinander, etwa 2 bei einer 1 und einer 3 als Note
	// spielt bei der Entscheidung klar <-> diskussionswürdig die ausschlaggebende Rolle
	return 0;

}

function getPartialScoreOfPaperForCriterion($paperId, $criterionId) {

	return 0;

}

function getPartialScoresOfPaperAsArrayForCriterion($paperId, $criterionId) {

	return array(0);

}

function getPartialScoreDifferenceOfPaperForCriterion($paperId, $criterionId) {

	// normierte Differenz der einzelnen Kriteriumsbenotungen zueinander, etwa 2 bei einer 1 und einer 3 als Note
	// spielt bei der Entscheidung klar <-> diskussionswürdig die ausschlaggebende Rolle
	return 0;

}

function getAllCompletelyReviewedPapers() {

	return array(array(0));

}

function getAllNotAtAllReviewedPapers() {

	return array(array(0));

}


function getAllPapersSortByTotalScore() {

	return array(array(0));

}

function getAllPapersSortByPartialScoreForCriterion($criterionId) {

	return array(array(0));

}

?>
