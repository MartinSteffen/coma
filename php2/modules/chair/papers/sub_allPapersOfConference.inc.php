<?
$confID = $_GET['confID'];

/* DEBUG START */
$papers = array();
$output = array();
$output['confID'] = $confID;
if ($confID==1)
{
	$output['confName'] = "Conference A";
	$paper = array();
	$paper['paperID'] = 1;
	$paper['paperName'] = "Paper A";
	$paper['paperDesc'] = "Description of paper A";	
	$paper['authorID'] = 1;
	$paper['authorName'] = "Author A";
	$papers['0'] = $paper;
	$paper = array();
	$paper['paperID'] = 2;
	$paper['paperName'] = "Paper B";
	$paper['paperDesc'] = "Description of paper B";	
	$paper['authorID'] = 1;
	$paper['authorName'] = "Author A";
	$papers['1'] = $paper;
	$paper = array();
	$paper['paperID'] = 3;
	$paper['paperName'] = "Paper C";
	$paper['paperDesc'] = "Description of paper C";	
	$paper['authorID'] = 2;
	$paper['authorName'] = "Author B";
	$papers['2'] = $paper;		
}

if ($confID==2)
{
	$output['confName'] = "Conference B";
	$paper = array();
	$paper['paperID'] = 4;
	$paper['paperName'] = "Paper D";
	$paper['paperDesc'] = "Description of paper D";	
	$paper['authorID'] = 3;
	$paper['authorName'] = "Author C";
	$papers['0'] = $paper;
	$paper = array();
	$paper['paperID'] = 5;
	$paper['paperName'] = "Paper E";
	$paper['paperDesc'] = "Description of paper E";	
	$paper['authorID'] = 4;
	$paper['authorName'] = "Author D";
	$papers['1'] = $paper;
	$paper = array();
	$paper['paperID'] = 6;
	$paper['paperName'] = "Paper F";
	$paper['paperDesc'] = "Description of paper F";	
	$paper['authorID'] = 2;
	$paper['authorName'] = "Author B";
	$papers['2'] = $paper;		
}
/* DEBUG END */

$output['papers'] = $papers;
$TPL['chair'] = $output;
template("CHAIR_allPapersOfConference");
$TPL['chair'] = "";
?>