<?

if ((! isset($_REQUEST['pid'])) or ($_REQUEST['pid'] == "")) {
  redirect("author", "view", "papers", "msg=Error:(author_edit_form) pid is mising");
}

$SQL = "SELECT author_id, title, abstract, filename, conference_id FROM paper WHERE id = " . $_REQUEST['pid'];
$result = $sql->query($SQL);
if ($result[0]['author_id'] != $_SESSION['userID']) {
	redirect("logout");
	}
foreach($result[0] as $key => $value) {
	$TPL[$key] = $value;
}

$SQL = "SELECT id, name FROM topic WHERE conference_id = " . $TPL['conference_id'];
$TPL['topic'] = $sql->query($SQL);




$SQL = "SELECT topic_id FROM isabouttopic WHERE paper_id = " . $_REQUEST['pid'];
$result = $sql->query($SQL);
foreach ($result as $value) {
	$TPL['topic_checked'][] = $value['topic_id'];
}


$TPL['pid'] = $_REQUEST['pid'];

template("AUTHOR_edit_form");

?>
