<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	if (isset($_REQUEST['paperID'])) {
	  $paperID = $_REQUEST['paperID'];
	} else {
	  $paperID = $_POST['paperlist'];
	}
	$SQL =
	  "SELECT * FROM reviewreport
	   WHERE (reviewreport.paper_id= ".$paperID.")
	   AND (reviewreport.reviewer_id = ".$_SESSION['userID'].")";

	$result = mysql_query($SQL);
	$reviewreport = array();
	if ($list = mysql_fetch_row($result))
	{
		$reviewreport = array("id"=>$list[0], "summary"=>$list[3],"remarks"=>$list[4],"confidential"=>$list[5]);
	}

	$TPL['reviewreport'] = $reviewreport;

    $SQL =
     "SELECT
        criterion.name, criterion.description, criterion.max_value, criterion.id, criterion.conference_id,
        paper.id, paper.filename, paper.title, paper.conference_id, paper.state,
        conference.id, conference.review_deadline
      FROM
        criterion INNER JOIN conference ON (criterion.conference_id = conference.id)
        INNER JOIN paper ON (conference.id = paper.conference_id)
      WHERE
            (paper.id = ".$paperID.")
        AND (conference.review_deadline >= CURRENT_DATE)
        AND (paper.state = 0)";

	$result = mysql_query($SQL);
	$count = 0;
	$criterionlist = array();

	while ($list = mysql_fetch_row ($result))
	{
		$SQL =
		  "SELECT * FROM rating
	  	    WHERE (review_id = ".$TPL['reviewreport']['id'].")
		    AND (criterion_id = ".$list[3].")";

		$res2 = mysql_query($SQL);
		$list2 = mysql_fetch_row($res2);

		$papertitle = $list[7];
		$paperfile = $list[6];

		$criterion = array();
		$criterion = array("name"=>$list[0], "text"=>$list[1], "max"=>$list[2], "id"=>$list[3], "grade"=>$list2[2], "comment"=>$list2[3]);

		$criterionlist[$count] = $criterion;
		$count++;
	}

	$TPL['criterionlist'] = $criterionlist;

	$paper = array();
	$paper = array("id"=>$paperID, "title"=>$papertitle, "file"=>$paperfile);

	$TPL['paper'] = $paper;

    template("REVIEWER_rate");

} else redirect("logout",false,false,"error=1");
?>