<?

$SQL = "SELECT conference_id, id, title, abstract, last_edited FROM paper WHERE author_id = ".$_SESSION['userID'];
$TPL = $sql->query($SQL);
foreach ($TPL as $row)
{
//	$SQL = "SELECT name FROM conference WHERE id = ".$row['conference_id'];
//	$result = $sql->query($SQL);
//	$row['conference_name'] = $result[0][0];
	$TMP[row][0] = "hallo";
	}
 var_dump($TPL);
 exit();



 
template('AUTHOR_view_papers')

?>
