<?

$sql = new SQL();
$sql->connect();
$conf = array();

function lock()
{
	// Sichert den exclusiven Zugriff
	mysql_query("LOCK TABLES conference, reviewreport, deniespaper, rejectedpapers WRITE");
}

function unlock()
{
	// Lock freigeben
	mysql_query("UNLOCK TABLES");
}

function checkrun()
{
	// Herausfinden, ob der Algorithmus laufen muss
	// Wir nehmen zunächst an, dass der Algorithmus nicht laufen muss
	$hastorun = false;
	global $conf;
	// Alle Konferenzen finden, an dem die Paper_Submission_Deadline abgelaufen ist.
	$SQL =
	 "SELECT conference.id, conference.paper_submission_deadline, conference.review_deadline
	 FROM conference
	 WHERE (conference.paper_submission_deadline < CURRENT_DATE)
	 AND (conference.review_deadline >= CURRENT_DATE)";

	$result = mysql_query($SQL);

	while ($list = mysql_fetch_row($result))
	{
		// Verteilte Paper aus dieser Konferenz finden
		$SQL =
		 "SELECT
		 reviewreport.paper_id,
		 paper.id, paper.conference_id,
		 conference.id
		 FROM reviewreport
		   INNER JOIN paper ON (reviewreport.paper_id = paper.id)
		   INNER JOIN conference ON (paper.conference_id = conference.id)
		 WHERE
		   (conference.id = $list[0])";

		$result2 = mysql_query($SQL);
		$count = 0;

		while ($list2 = mysql_fetch_row($result2))
		{
			$count++;
		}
		if ($count == 0)
		{
			// Noch keine Papiere verteilt, der Alg. muss laufen ....
			// Annahme: Es gibt auch Paper zu verteilen ...
			$hastorun = true;
			$conf[] = $list[0];
		}

		// Möglicherweise Einträge in RejectedPapers für diese Konferenz?
		$SQL =
		 "SELECT rejectedpapers.person_id, rejectedpapers.paper_id,
		  paper.id, paper.conference_id
		 FROM rejectedpapers INNER JOIN paper ON (rejectedpapers.paper_id = paper.id)
		 WHERE (paper.conference_id = ".$list[0].")";

		$result2 = mysql_query($SQL);
		$count = 0;
		while ($list2 = mysql_fetch_row($result2))
		{
			// Eintrag gehört in Deniespaper
			mysql_query("INSERT INTO deniespaper VALUES('".$list2[0]."', '".$list2[1]."')");
			$count++;
		}
		if ($count > 0)
		{
			// Es gab Reviewer, die ein Paper nicht bewerten wollen
			$hastorun = true;
			$conf[] = $list[0];
			// Die abgelehnten Paper aus RejectedPapers löschen
			mysql_query("DELETE FROM rejectedpapers");
		}
	}
	return $hastorun;
}

function check_coauthor($person, $paper)
{
	$SQL =
	"SELECT COUNT(*) FROM iscoauthorof
	 WHERE (person_id = ".$person.")
	 AND (paper_id = ".$paper.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return ($count == 0);
}

function check_denied($person, $paper)
{
	$SQL =
	"SELECT COUNT(*) FROM deniespaper
	 WHERE (person_id = ".$person.")
	 AND (paper_id = ".$paper.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return ($count == 0);
}

function check_exclude($person, $paper)
{
	$SQL =
	"SELECT COUNT(*) FROM excludespaper
	 WHERE (person_id = ".$person.")
	 AND (paper_id = ".$paper.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return ($count == 0);
}

function check_review($person, $paper)
{
	$SQL =
	"SELECT COUNT(*) FROM reviewreport
	 WHERE (reviewer_id = ".$person.")
	 AND (paper_id = ".$paper.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return ($count == 0);
}

function check_prefer($person, $paper)
{
	$SQL =
	"SELECT COUNT(*) FROM preferspaper
	 WHERE (person_id = ".$person.")
	 AND (paper_id = ".$paper.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return ($count > 0);
}

function check_topic($person, $paper)
{
	$SQL =
	"SELECT COUNT(*)
	FROM preferstopic INNER JOIN isabouttopic ON (preferstopic.topic_id = isabouttopic.topic_id)
	WHERE (isabouttopic.paper_id = ".$paper.")
	AND (preferstopic.person_id = ".$person.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return ($count > 0);
}

function getreviewcount($person)
{
	$SQL =
	"SELECT COUNT(*)
	FROM reviewreport
	WHERE (reviewer_id = ".$person.")";

	$result = mysql_query($SQL);
	$list = mysql_fetch_row($result);
	$count = $list[0];

	return $count;
}

function addreviewer($person, $paper)
{
	$result = mysql_query("INSERT INTO reviewreport VALUES(0, ".$paper.", ".$person.", NULL, NULL, NULL)");
}

function ptraMain()
{
	global $conf;
	lock();
	if (checkrun())
	{
		// Es sind Paper zu verteilen
		foreach ($conf as $wert)
		{
			// wir müssen für die Konferenz mit der id "wert" tätig werden.

			// Anzahl der Reviewer für ein Paper bestimmen.
			$SQL = "SELECT conference.id, conference.min_reviews_per_paper FROM conference WHERE (conference.id = ".$wert.")";
			$result = mysql_query($SQL);
			$list = mysql_fetch_row($result);
			$minrev = $list[1];

			// Alle zu verteilenden Paper in dieser Konferenz bestimmen.

			$SQL =
			  "SELECT paper.id, paper.conference_id, paper.author_id, paper.state FROM paper
			   WHERE (paper.conference_id = ".$wert.")
			   AND (paper.state = 0)";

			$result = mysql_query($SQL);
			while ($list = mysql_fetch_row($result))
			{
				// Muss nicht erneut verteilt werden, falls es bereits genug Reviewer gibt.
				$SQL =
				  "SELECT COUNT(*) FROM paper INNER JOIN reviewreport ON (paper.id = reviewreport.paper_id)
				   WHERE (paper.id = ".$list[0].")";

				$res = mysql_query($SQL);
				$ll = mysql_fetch_row($res);
				$papercount = $minrev - $ll[0]; // Die Anzahl der noch nötigen Reviewer

				if ($papercount > 0)
				{
					// zu diesem Paper mögliche Reviewer bestimmen
					$SQL =
					"SELECT role.conference_id, role.person_id, role.role_type, role.state
					FROM role
					WHERE (role.conference_id = ".$wert.")
					AND (role.person_id != ".$list[2].")
					AND (role.role_type = 3)
					AND (role.state = 1)";

					$result2 = mysql_query($SQL);

					$kandidat = array();

					while ($list2 = mysql_fetch_row($result2))
					{
						// Möglicher Kandidat, der Reviewer für diese Konferenz ist und nicht Author des Papers
						$darf = true;
						if ($darf == true) { $darf = check_coauthor($list2[1], $list[0]); }
						if ($darf == true) { $darf = check_denied($list2[1], $list[0]); }
						if ($darf == true) { $darf = check_exclude($list2[1], $list[0]); }
						if ($darf == true) { $darf = check_review($list2[1], $list[0]); }

						if ($darf == true)
						{
							// Möglichen Kandidaten merken
							$kandidat[] = $list2[1];
						}
					}

					// Alle Möglichen Kandidaten sind in $kandidat
					$pt = array();
					$rest = array();

					foreach ($kandidat as $personid)
					{
						// Gibt es Kandidaten, die sich das Paper wünschen ?
						if (check_prefer($personid, $list[0]))
						{
							// Direkt zuteilen, aber $papercount dekrementieren
							addreviewer($personid, $list[0]);
							$papercount--;
						} else
						// Gibt es Kandidaten, die sich das Thema des Papers wünschen ?
						if (check_topic($personid, $list[0]))
						{
							// Space for improvement : Noch fairer wäre, die Reviewer auszuwählen, bei denen am meisten Topics mit dem
							// Paper übereinstimmen. Wir wählen hier einen Reviewer zufällig aus, bei dem mindestens ein Topic übereinstimmt
							$pt[]=$personid;
						} else
						// Die letzten beißen die Hunde :-)
						{
							$rest[]=array(getreviewcount($personid), $personid);
						}
					}
					while (min(count($pt), $papercount) > 0)
					{
						// Einen Reviewer zufällig auswählen
						$selected = rand(0, count($pt)-1);
						addreviewer($pt[$selected], $list[0]);
						$papercount--;
						unset($pt[$selected]);
					}
					sort($rest);
					while (min(count($rest), $papercount) > 0)
					{
						addreviewer($rest[0][1], $list[0]);
						$papercount--;
						unset($rest[0]);
					}
				}
			}
		}
	}
	unlock();
}

ptraMain();


?>