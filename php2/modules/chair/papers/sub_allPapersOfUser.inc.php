<?
$userID = $_GET['userID'];

/* DEBUG START */
$papers = array();
$output = array();
$output['userID'] = $userID;
if ($userID==1)
{
	$output['userName'] = "Author A";
	$paper = array();
	$paper['paperID'] = 1;
	$paper['paperName'] = "Paper A";
	$paper['paperDesc'] = "Description of paper A";	
	$paper['condID'] = 1;
	$paper['confName'] = "Conference A";
	$papers['0'] = $paper;
	$paper = array();
	$paper['paperID'] = 2;
	$paper['paperName'] = "Paper B";
	$paper['paperDesc'] = "Description of paper B";	
	$paper['condID'] = 1;
	$paper['confName'] = "Conference A";
	$papers['1'] = $paper;	
}
if ($userID==2)
{
	$output['userName'] = "Author B";
	$paper = array();
	$paper['paperID'] = 3;
	$paper['paperName'] = "Paper C";
	$paper['paperDesc'] = "Description of paper C";	
	$paper['condID'] = 1;
	$paper['confName'] = "Conference A";
	$papers['0'] = $paper;
	$paper = array();
	$paper['paperID'] = 6;
	$paper['paperName'] = "Paper F";
	$paper['paperDesc'] = "Description of paper F";	
	$paper['condID'] = 2;
	$paper['confName'] = "Conference B";
	$papers['1'] = $paper;	
}
if ($userID==3)
{
	$output['userName'] = "Author C";
	$paper = array();
	$paper['paperID'] = 4;
	$paper['paperName'] = "Paper D";
	$paper['paperDesc'] = "Description of paper D";	
	$paper['condID'] = 2;
	$paper['confName'] = "Conference B";
	$papers['0'] = $paper;
}
if ($userID==4)
{
	$output['userName'] = "Author D";
	$paper = array();
	$paper['paperID'] = 5;
	$paper['paperName'] = "Paper E";
	$paper['paperDesc'] = "Description of paper E";	
	$paper['condID'] = 2;
	$paper['confName'] = "Conference B";
	$papers['0'] = $paper;
}

/* DEBUG END */

$output['papers'] = $papers;
$TPL['chair'] = $output;
template("CHAIR_allPapersOfUser");
$TPL['chair'] = "";
?>