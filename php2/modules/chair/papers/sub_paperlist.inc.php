<?
$sql = new SQL();
$sql->connect();

include("includes/rtp.lib.php");

if(isChair_Conference($_GET['confID'] ) )
{

$paperlist = getAllPapersForConferenceSortByTotalScore($_GET['confID']);

foreach($paperlist as $paper) {
	$paper['author'] = getPerson($paper['author_id']);
}

$TPL['paperlist'] = $paperlist;

template("CHAIR_listPapers");

}
else redirect("logout",false,false,"error=1");	
?>