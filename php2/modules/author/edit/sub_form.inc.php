<?

if ((! isset($_REQUEST['pid'])) or ($_REQUEST['pid'] == "")) {
  redirect("author", "view", "papers", "msg=Error:(author_edit_form) pid is mising");
}

$SQL = "SELECT author_id, title, abstract, filename FROM paper WHERE id = " . $_REQUEST['pid'];
$result = $sql->query($SQL);
if ($result[0]['author_id'] != $_SESSION['userID']) {
	redirect("logout");
	}
foreach($result[0] as $key => $value) {
	$TPL[$key] = $value;
}
$TPL['pid'] = $_REQUEST['pid'];

template("AUTHOR_edit_form");

?>
