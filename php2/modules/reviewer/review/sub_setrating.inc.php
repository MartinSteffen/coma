<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
  $SQL =
     "SELECT
        criterion.name, criterion.description, criterion.max_value, criterion.id, criterion.conference_id,
        paper.id, paper.filename, paper.title, paper.conference_id, paper.state,
        conference.id, conference.review_deadline
      FROM
        criterion INNER JOIN conference ON (criterion.conference_id = conference.id)
        INNER JOIN paper ON (conference.id = paper.conference_id)
      WHERE
            (paper.id = ".$_POST['paperID'].")
        AND (conference.review_deadline >= CURRENT_DATE)
        AND (paper.state = 0)";

	$result = mysql_query($SQL);
	$count = 0;
	$criterionlist = array();

	while ($list = mysql_fetch_row ($result))
	{
		$criterion = array();
		$criterion = array("id"=>$list[3], "value"=>$_POST['G'.$list[0]], "comment"=>$_POST['K'.$list[0]]);

		$criterionlist[$count] = $criterion;
		$count++;
	}

	for ($i=0; $i<$count; $i++)
	{
		$SQL =
		  "SELECT * FROM rating
		  WHERE (review_id = ".$_POST['reviewreportID'].")
		    AND (criterion_id = ".$criterionlist[$i]['id'].")";

		$result = mysql_query($SQL);
		if (mysql_fetch_row($result))
		{
			// Datensatz bereits vorhanden, nur update durchführen
			$SQL =
			  "UPDATE rating SET grade = ".$criterionlist[$i]['value']." , comment = ".$criterionlist[$i]['comment']."
			  WHERE (review_id = ".$_POST['reviewreportID'].")
				AND (criterion_id = ".$criterionlist[$i]['id'].")";
			$result = mysql_query($SQL);
		} else {
			$SQL =
			  "INSERT INTO rating
			  VALUES (".$_POST['reviewreportID'].", ".$criterionlist[$i]['id'].", ".$criterionlist[$i]['value'].", ".$criterionlist[$i]['comment'].")";
			$result = mysql_query($SQL);
		}
	}

	$SQL =
	  "UPDATE reviewreport SET summary = ".$_POST['summary']." , remarks = ".$_POST['remarks']." , confidential = ".$_POST['confidential']."
	  WHERE (paper_id = ".$_POST['paperID'].")
	  AND (reviewer_id = ".$_SESSION['userID'].")";

    $result = mysql_query($SQL);

  redirect("tasks",false,false,false);
} else redirect("logout",false,false,"error=1");
?>