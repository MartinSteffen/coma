<?
$sql = new SQL();
$sql->connect();

include("includes/rtp.lib.php");

if(isChair_Conference($_GET['confID'] ) )
{
$TPL['paperlist'] = getAllPapersForConferenceSortByTotalScore($_GET['confID']);
template("CHAIR_listPapers");

}
else redirect("logout",false,false,"error=1");	
?>