<?

$SQL = "SELECT conference_id, id, title, abstract, last_edited FROM paper WHERE author_id = ".$_SESSION['userID'];
$TPL = $sql->query($SQL);
foreach ($TPL as $key => $value)
{
	$SQL = "SELECT name FROM conference WHERE id = ".$value['conference_id'];
	$result = $sql->query($SQL);
	$TPL[$key]['conference_name'] = $result[0][0];
	}



 
template('AUTHOR_view_papers')

?>
