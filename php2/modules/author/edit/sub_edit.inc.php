<?
if ((! isset($_REQUEST['pid'])) or ($_REQUEST['pid'] == "")) {
  redirect("author", "view", "papers", "msg=Error:(author_edit_edit) pid is missing");
}
$paper_id = $_REQUEST['pid'];

$SQL = "SELECT conference_id FROM paper WHERE id = " . $paper_id;
$conference_id = $sql->query($SQL);
$conference_id = $conference_id[0]['conference_id'];

// make possible changes in topics

$SQL = "SELECT id, name FROM topic WHERE conference_id = " . $conference_id;
$topic = $sql->query($SQL);
$topic_now = array();
foreach ($topic as $value) {
	if (isset ($_REQUEST[$value['id']])) {
		$topic_now[] = $_REQUEST[$value['id']];
	}
}

$SQL = "SELECT topic_id FROM isabouttopic WHERE paper_id = " . $_REQUEST['pid'];
$result = $sql->query($SQL);
$topic_checked = array();
foreach ($result as $value) {
	$topic_checked[] = $value['topic_id'];
}

$topic_to_uncheck = array_diff ($topic_checked, $topic_now);
$topic_to_check = array_diff ($topic_now, $topic_checked);
foreach ($topic_to_check as $value) {
	$SQL = "INSERT INTO isabouttopic (paper_id, topic_id) VALUES ('" . $paper_id . "', '" . $value . "')";
	$result = $sql->insert($SQL);
}
foreach ($topic_to_uncheck as $value) {
	$SQL = "DELETE FROM isabouttopic WHERE paper_id = " . $paper_id . " AND topic_id = " . $value;
	$result = $sql->insert($SQL);
}
/*
var_dump($topic);
var_dump($topic_checked);
var_dump($topic_now);
var_dump($topic_to_check);
var_dump($topic_to_uncheck);
exit();
*/

// make possible changes in paper

if ($_FILES['file']['size'] > 0) {

	// var_dump($_REQUEST);
	if ((! isset($_REQUEST['file'])) or ($_REQUEST['file'] == "")) {
		redirect("author", "view", "papers", 'msg=Error:(author_edit_edit) $_REQUEST[filename] is missing');
	}

	// remember the old filename for deleting the old file (only one version at the same time :-) )
	$oldfilename = $_REQUEST['filename'];

	// get the new mime_type, it could be different
	$mime_type = $_FILES['file']['type'];
	
	//get filenameextension from filename
	$name = $_FILES['file']['name'];
	//but only there is a filename
	if ($name == "") {
		redirect("author", "new", "create", "cid=" . $conference_id . "&msg=<font style=color:red>Please select a valid file to upload.</font>");
	}
	$a = explode(".", $name);
	//or a filenameextension
	if (count($a) > 1) {
		$a = array_reverse($a);
		$ending = $a[0];
	}else{
		$ending = "";
	}
	
	// get fTP handle
	$ftphandle=@ftp_connect($ftphost);

	// errorhandling, to be improved
	if (!$ftphandle) {
		redirect("author", "view", "papers", "msg = Error:(author_edit_edit) ftp_connect failed");
	}
	
	// login or give errormessage if login failed
	if (! @ftp_login($ftphandle, $ftpuser, $ftppass)) {
		redirect("author", "view", "papers", "msg= Error:(author_edit_edit) ftp_login failed");
	}

	$ftppath="./" . $ftpdir . "/" . $paper_id;

	// changedir to $ftpdir ausser config, darunter Verzeichnis $paperid, oder wirf Fehler
	if (! @ftp_chdir($ftphandle, $ftppath)) {
		redirect("author", "view", "papers", "msg=Error:(author_edit_edit) ftp_chdir failed");
	}
	
	$remotefilename = $paper_id .".". $ending;
	$localfilename = $_FILES['file']['tmp_name'];

	// delete old version of file
	if ($oldfilename != "") {
		if (! @ftp_delete($ftphandle, $oldfilename)) {
			redirect("author", "view", "papers", "msg=Error:(author_edit_edit) ftp_delete failed");
		}
	}

	// put file, auto-creating a filename styled $paperid.$ending, or die in error
	if (! @ftp_put($ftphandle, $remotefilename, $localfilename ,FTP_BINARY)) {
		redirect("author", "view", "papers", "msg=Error:(author_edit_edit) ftp_put failed");
	} 

	// change SQL entries
	$SQL = "UPDATE paper SET title = '" . htmlentities ($_REQUEST['title'], ENT_QUOTES) . "', abstract = '" . htmlentities ($_REQUEST['summary']. ENT_QUOTES) . "', filename = '" . $remotefilename . "', mime_type = '" . $mime_type . "', last_edited = '" . date('Y-m-d') . "' WHERE id = " . $_REQUEST['pid'];
	$result = $sql->insert($SQL);

	// close ftp connection
	@ftp_quit($ftphandle);
}
else {
	// change only the rest
	$SQL = "UPDATE paper SET title = '" . htmlentities ($_REQUEST['title'], ENT_QUOTES) . "', abstract = '" . htmlentities ($_REQUEST['summary'], ENT_QUOTES) . "', last_edited = '" . date('Y-m-d') . "' WHERE id = " . $_REQUEST['pid'];
	$result = $sql->insert($SQL);
}

redirect("author", "view", "papers", "msg=Successfully edited your paper.");
	
?>
