<?
$content = array();

//check for conference_id
if (isset($_REQUEST['cid'])) {
	$content['conference_id'] = $_REQUEST['cid'];
}else{
  echo('Error(m=author&a=new&s=save): no conference_id in "$_REQUEST"');
  exit();
}


// check if title is valid
if ($_REQUEST['title'] == "") {
	redirect("author", "new", "create", "cid=". $content['conference_id']."&msg=<font style=color:red>Please enter a title for your paper.</font>");
}


// get filenameextension from filename
if ((isset ($_FILES['file'])) and ($_FILES['file']['size'] > 0)) {
	$name = $_FILES['file']['name'];
// but only there is a filename
	if ($name == "") {
		redirect("author", "new", "create", "cid=". $content['conference_id']."&msg=<font style=color:red>Please select a valid file to upload.</font>");
	}
	$a = explode(".", $name);
// or a filenameextension
	if (count($a) > 1) {
		$a = array_reverse($a);
		$ending = $a[0];
	}else{
		$ending = "";
	}
	$content['mime_type'] = $_FILES['file']['type'];
		
	$remotefilename = $paper_id .".". $ending;
	$localfilename = $_FILES['file']['tmp_name'];

}
else {
	$content['mime_type'] = "";
}


// check for topiccheckboxes
$SQL = "SELECT id FROM topic WHERE conference_id = ". $content['conference_id'];
$topic = $sql->query($SQL);
foreach ($topic as $value) {
	if (isset ($_REQUEST[$value['id']])) {
		$content['topic'][] = $_REQUEST[$value['id']];
	}
}


// check for coauthors
/*
if ($_REQUEST['coauthor'] != "") {
	$coauthors = $_REQUEST['coauthor'];
	$coauthors = explode("\n", $coauthors);
	dump($coauthors);
	foreach ($coautors as $k => $v) {
		$coautors[$k] = explode(",", $v);
	}
	foreach ($coautors as $k => $v) {
		$coautors[$k] = explode(";", $v);
	}
	foreach ($coautors as $k => $v) {
		$coautors[$k] = explode(":", $v);
	}
	foreach ($coauthors as $key => $value) {
		if ($value == "") {
			unset ($coauthors[$key]);
		}
	}
}
dump($coauthors);
*/


//fill content array with values for db
$content['author_id'] = $_SESSION['userID'];
$content['title'] = htmlentities ($_REQUEST['title'], ENT_QUOTES);
$content['abstract'] = htmlentities ($_REQUEST['summary'], ENT_QUOTES);
$content['last_edited'] = date('Y-m-d');
$content['filename'] = "";
$content['state'] = 0;



//insert new db entry
$SQL = "INSERT INTO paper (conference_id, author_id, title, abstract, last_edited, filename, state, mime_type) VALUES ('".$content['conference_id']."', '".$content['author_id']."', '".$content['title']."', '".$content['abstract']."', '".$content['last_edited']."', '".$content['filename']."', '".$content['state']."', '".$content['mime_type']."')";
$result = $sql->insert($SQL); 

//get paper_id
$paper_id = mysql_insert_id();

// insert topics
foreach ($content['topic'] as $value) {
	$SQL = "INSERT INTO isabouttopic (paper_id, topic_id) VALUES ('" . $paper_id . "', '" . $value . "')";
	$result = $sql->insert($SQL);
}

// insert coauthors
/*
foreach ($coauthors as $value) {
	$SQL = "INSERT INTO iscoauthorof (person_id, paper_id, name) VALUES ('" . $content['author_id'] . "', '" . $paper_id . "', '" . $value . "')";
	$result = $sql->insert($SQL);
}
*/

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


if ((isset ($_FILES['file'])) and ($_FILES['file']['size'] > 0)) {
	
// changedir to $ftpdir ausser config, darunter Verzeichnis $paperid, oder wirf Fehler
	if (! @ftp_chdir($ftphandle, $ftppath)) {
		$msg = cleanup_ftp($paper_id, 4, $ftphandle); //die("FTP-chdir to ".$ftppath." fehlgeschlagen!");
		redirect("author", "view", "papers", "msg=". $msg);
	}

// put file, auto-creating a filename styled $paperid.$ending, or die in error
	if (! @ftp_put($ftphandle, $remotefilename, $localfilename ,FTP_BINARY)) {
		$msg = cleanup_ftp($paper_id, 5, $ftphandle); //die("Hochladen nach '$ftppath / $remotefilename' irgendwie fehlgeschlagen. Schade. Aber ich hab noch Erdbeertoertchen...");
		redirect("author", "view", "papers", "msg=". $msg);
	}
       
	$SQL = "UPDATE paper SET filename = '".$remotefilename."' WHERE id = ".$paper_id."";
	$change_result = $sql->insert($SQL);
}

// do not forget to close connection. Your FTP-provider says thanks :)
// ftp_close() throws error, so use here ftp_quit()
ftp_quit($ftphandle);


redirect("author", "view", "papers");
//done at last! Good luck!
?>
