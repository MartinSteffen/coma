<?

$SQL = "SELECT conference_id, id, title, abstract, last_edited FROM paper WHERE author_id = ".$_SESSION['userID'];
$TPL = $sql->query($SQL);
foreach ($TPL as $key => $value)
{
	$date = $TPL[$key]['last_edited'];
	$date = explode(" ", $date);
	$date = $date[0];
	$TPL[$key]['last_edited'] = $date;
	$SQL = "SELECT name FROM conference WHERE id = ".$value['conference_id'];
	$result = $sql->query($SQL);
	$TPL[$key]['conference_name'] = $result[0][0];
	}

if (isset ($_REQUEST['msg'])) {
	$msg = $_REQUEST['msg'];
	$TPL['msg'] = $msg;
}

template('AUTHOR_view_papers')

?>
