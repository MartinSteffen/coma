<?
$paperID = $_GET['paperID'];
$paper = array();
$paper['paperID'] = $paperID;

/* DEBUG START */ 
if($paperID == 1)
{
	$paper['paperName'] = "Paper A";
	$paper['paperDesc'] = "Description for paper A";
	$paper['authorID'] = 1;
	$paper['authorName'] = "Author A";
	$paper['confID'] = 1;
	$paper['confName'] = "Conference A";
}
if($paperID == 2)
{
	$paper['paperName'] = "Paper B";
	$paper['paperDesc'] = "Description for paper B";
	$paper['authorID'] = 1;
	$paper['authorName'] = "Author A";
	$paper['confID'] = 1;
	$paper['confName'] = "Conference A";
}
if($paperID == 3)
{
	$paper['paperName'] = "Paper C";
	$paper['paperDesc'] = "Description for paper C";
	$paper['authorID'] = 2;
	$paper['authorName'] = "Author B";
	$paper['confID'] = 1;
	$paper['confName'] = "Conference A";
}
if($paperID == 4)
{
	$paper['paperName'] = "Paper D";
	$paper['paperDesc'] = "Description for paper D";
	$paper['authorID'] = 3;
	$paper['authorName'] = "Author C";
	$paper['confID'] = 2;
	$paper['confName'] = "Conference B";
}
if($paperID == 5)
{
	$paper['paperName'] = "Paper E";
	$paper['paperDesc'] = "Description for paper E";
	$paper['authorID'] = 4;
	$paper['authorName'] = "Author D";
	$paper['confID'] = 2;
	$paper['confName'] = "Conference B";
}
if($paperID == 6)
{
	$paper['paperName'] = "Paper F";
	$paper['paperDesc'] = "Description for paper F";
	$paper['authorID'] = 2;
	$paper['authorName'] = "Author B";
	$paper['confID'] = 2;
	$paper['confName'] = "Conference B";
}
/* DEBUG END */


$TPL['chair'] = $paper;
template("CHAIR_paper");
$TPL['chair'] = "";
?>