<?
$sql = new SQL();
$sql->connect();

include("includes/rtp.lib.php");

if(isChair_Conference($_GET['confID'] ) )
{

if($_REQUEST['accept']) {
	$query="UPDATE paper SET state=3 where id=".$_REQUEST['accept'];
	$result=$sql->insert($query);
} else if($_REQUEST['reject']) {
	$query="UPDATE paper SET state=4 where id=".$_REQUEST['reject'];
	$result=$sql->insert($query);
} else if($_REQUEST['reopen']) {
	makePaperState($_REQUEST['reopen']);
}


$paperlist = getAllPapersForConferenceSortByTotalScore($_GET['confID']);

$paperlist_accepted = array();
$paperlist_rejected = array();
$paperlist_open = array();
$paperlist_nothing = array();

$i=1;

$old_paper=array("total_grade" => -1, "state" => -1);

foreach($paperlist as $paper) {
	$paper['author'] = getPerson($paper['author_id']);

	if (compareGrade($paper, $old_paper)==0) {
		$paper['rank']=$old_paper['rank'];
		$i++;
	} else {
		$paper['rank']=$i++;
	}

	if ($paper['state']==3) {
		$paperlist_accepted[ ] = $paper;
	} else if ($paper['state']==4) {
		$paperlist_rejected[ ] = $paper;
	} else if ($paper['review_count']==0) {
		$paperlist_nothing[]=$paper;
	} else {
		$paperlist_open[]=$paper;
	}

	$old_paper=$paper;
}

$TPL['paperlist_open'] = $paperlist_open;

$TPL['paperlist_accepted'] = $paperlist_accepted;

$TPL['paperlist_rejected'] = $paperlist_rejected;

$TPL['paperlist_nothing'] = $paperlist_nothing;

template("CHAIR_listPapers");

}
else redirect("logout",false,false,"error=1");	
?>