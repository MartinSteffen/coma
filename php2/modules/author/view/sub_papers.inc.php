<?
if (! isset ($_SESSION['userID'])) {
	redirect("logout", false, false, "error=1");
}



$SQL = "SELECT conference_id FROM paper WHERE author_id = " . $_SESSION['userID'];
$result = $sql->query($SQL);
// dump ($result);
$conf_list = array();
foreach ($result as $row) {
	$conf_list[] = $row['conference_id'];
}

// var_dump ($conf_list);

$SQL = "SELECT id FROM conference WHERE (paper_submission_deadline < CURRENT_DATE)";
$result = $sql->query($SQL);

$dead_conf_list = array();
foreach ($result as $row) {
	$dead_conf_list[] = $row['id'];
}

// var_dump($dead_conf_list);

$dead_conf = array_intersect($conf_list, $dead_conf_list);
$alive_conf = array_diff($conf_list, $dead_conf_list);

// var_dump($dead_conf);
// var_dump($alive_conf);

$TPL = array();

foreach($alive_conf as $row) {
	$SQL = "SELECT conference_id, id, title, abstract, last_edited FROM paper WHERE conference_id = " . $row . " AND author_id = ".$_SESSION['userID'];
	$result = $sql->query($SQL);
	foreach ($result as $key => $value) {
		$date = $result[$key]['last_edited'];
		$date = explode(" ", $date);
		$date = $date[0];
		$result[$key]['last_edited'] = $date;
	}
	$TPL['alive'][$row] = $result;
	$TPL['alive'][$row]['conference_id'] = $row;
	$SQL = "SELECT name FROM conference WHERE id = ". $row;
	$result = $sql->query($SQL);
	$TPL['alive'][$row]['conference_name'] = $result[0][0];
}

foreach($dead_conf as $row) {
	$SQL = "SELECT conference_id, id, title, abstract, last_edited FROM paper WHERE conference_id = " . $row . " AND author_id = ".$_SESSION['userID'];
	$result = $sql->query($SQL);
	foreach ($result as $key => $value) {
		$date = $result[$key]['last_edited'];
		$date = explode(" ", $date);
		$date = $date[0];
		$result[$key]['last_edited'] = $date;
	}
	$TPL['dead'][$row] = $result;
	$TPL['dead'][$row]['conference_id'] = $row;
	$SQL = "SELECT name FROM conference WHERE id = ". $row;
	$result = $sql->query($SQL);
	$TPL['dead'][$row]['conference_name'] = $result[0][0];
}


// dump($TPL);

if (isset ($_REQUEST['msg'])) {
	$msg = $_REQUEST['msg'];
	$TPL['msg'] = $msg;
}

template('AUTHOR_view_papers')
?>
