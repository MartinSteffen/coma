<?
$content = array();

//check for conference_id
if (isset($_REQUEST['cid'])) {
	$content['conference_id'] = $_REQUEST['cid'];
}else{
  echo('Error(m=author&a=new&s=save): no conference_id in "$_REQUEST"');
  exit();
}

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

if ($_REQUEST['title'] == "") {
	redirect("author", "new", "create", "cid=". $content['conference_id']."&msg=<font style=color:red>Please enter a title for your paper.</font>");
}

//fill content array with values for db

$content['author_id'] = $_SESSION['userID'];

/*
//check if the paper is already uploaded (, via reload e.g.)
$SQL = "SELECT id FROM paper WHERE author_id = ". $content['author_id'] ." AND conference_id = ". $content[conference_id];
$result = $sql->query($SQL);
if (count($result) > 0) {
	redirect("author", "view", "papers");
}
*/
//if okay, proceed
$content['title'] = eregi_replace(">", "&gt;", eregi_replace("<", "&lt;", $_REQUEST['title']));
$content['abstract'] = eregi_replace(">", "&gt;", eregi_replace("<", "&lt;", $_REQUEST['summary']));
$content['last_edited'] = date('Y-m-d');
$content['filename'] = "";
$content['state'] = 0;
$content['mime_type'] = $_FILES['file']['type'];


//insert new db entry
$SQL = "INSERT INTO paper (conference_id, author_id, title, abstract, last_edited, filename, state, mime_type) VALUES ('".$content['conference_id']."', '".$content['author_id']."', '".$content['title']."', '".$content['abstract']."', '".$content['last_edited']."', '".$content['filename']."', '".$content['state']."', '".$content['mime_type']."')";
$result = $sql->insert($SQL); 


//get paper_id
$paper_id = mysql_insert_id();

// get fTP handle
$ftphandle = @ftp_connect($ftphost);

// errorhandling, to be improved
if (!$ftphandle) {
	$msg = cleanup_ftp($paper_id, 1, $ftphandle);
	redirect("author", "view", "papers", "msg=". $msg);
//	die("Gott ist scheisse! FTP-host unauffindbar!");
}
// login or give errormessage if login failed
if (! @ftp_login($ftphandle, $ftpuser, $ftppass)) {
	$msg = cleanup_ftp($paper_id, 2, $ftphandle);
	redirect("author", "view", "papers", "msg=". $msg);
}


$ftppath="./" . $ftpdir . "/" . $paper_id;

if (! @ftp_mkdir($ftphandle, $ftppath)) {
	$msg = cleanup_ftp($paper_id, 3, $ftphandle); //die("ftp_mkdir ".$ftppath." fehlgeschlagen!");
	redirect("author", "view", "papers", "msg=". $msg);
}

// changedir to $ftpdir ausser config, darunter Verzeichnis $paperid, oder wirf Fehler
if (! @ftp_chdir($ftphandle, $ftppath)) {
	$msg = cleanup_ftp($paper_id, 4, $ftphandle); //die("FTP-chdir to ".$ftppath." fehlgeschlagen!");
	redirect("author", "view", "papers", "msg=". $msg);
}
	
$remotefilename = $paper_id .".". $ending;
$localfilename = $_FILES['file']['tmp_name'];

// put file, auto-creating a filename styled $paperid.$ending, or die in error
if (! @ftp_put($ftphandle, $remotefilename, $localfilename ,FTP_BINARY)) {
	$msg = cleanup_ftp($paper_id, 5, $ftphandle); //die("Hochladen nach '$ftppath / $remotefilename' irgendwie fehlgeschlagen. Schade. Aber ich hab noch Erdbeertoertchen...");
	redirect("author", "view", "papers", "msg=". $msg);
}
       
$SQL = "UPDATE paper SET filename = '".$remotefilename."' WHERE id = ".$paper_id."";
$change_result = $sql->insert($SQL);

// do not forget to close connection. Your FTP-provider says thanks :)
// ftp_close() throws error, so use here ftp_quit()
ftp_quit($ftphandle);

redirect("author", "view", "papers");
//done at last! Good luck!
?>
