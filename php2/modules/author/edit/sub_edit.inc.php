<?
if ((! isset($_REQUEST['pid'])) or ($_REQUEST['pid'] == "")) {
  redirect("author", "view", "papers", "msg=Error:(author_edit_edit) pid is missing");
}
$paper_id = $_REQUEST['pid'];


if ($_FILES['file']['size'] > 0) {

	if ((! isset($_REQUEST['filename'])) or ($_REQUEST['filename'] == "")) {
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
		redirect("author", "new", "create", "cid=". $content['conference_id']."&msg=<font style=color:red>Please select a valid file to upload.</font>");
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
	if (! @ftp_delete($ftphandle, $oldfilename)) {
		redirect("author", "view", "papers", "msg=Error:(author_edit_edit) ftp_delete failed");
	}

	// put file, auto-creating a filename styled $paperid.$ending, or die in error
	if (! @ftp_put($ftphandle, $remotefilename, $localfilename ,FTP_BINARY)) {
		redirect("author", "view", "papers", "msg=Error:(author_edit_edit) ftp_put failed");
	} 

	// change SQL entries
	$SQL = "UPDATE paper SET title = '" . $_REQUEST['title'] . "', abstract = '" . $_REQUEST['summary'] . "', filename = '" . $remotefilename . "', mime_type = '" . $mime_type . "' WHERE id = " . $_REQUEST['pid'];
	$result = $sql->insert($SQL);

	// close ftp connection
	@ftp_quit($ftphandle);
}
else {
	// change only the rest
	$SQL = "UPDATE paper SET title = '" . $_REQUEST['title'] . "', abstract = '" . $_REQUEST['summary'] . "' WHERE id = " . $_REQUEST['pid'];
	$result = $sql->insert($SQL);
}

redirect("author", "view", "papers", "msg=Successfully edited your paper.");
	
?>
