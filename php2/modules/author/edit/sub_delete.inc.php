<?

if (isset ($_REQUEST['pid']) and ($paper_id = $_REQUEST['pid']) != "") {
	$SQL = "SELECT author_id FROM paper WHERE id = " . $paper_id;
	$result = $sql->query($SQL);
	if ($result[0]['author_id'] != $_SESSION['userID']) {
		redirect("logout");
	}
}else{
	redirect("author", "view", "papers", "msg=<font class=textBold style=color:red>pid is missing! (redirected by author_edit_logout)</font>");
}

$msg = cleanup_ftp($paper_id);
redirect("author", "view", "papers", "msg=". $msg);
?>
