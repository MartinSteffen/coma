<?

//get fileending from filename
$name = $_REQUEST['file']['name'];
$a = explode(".", $name);
$a = array_reverse($a);
$ending = $a[0];


$content = array();

//check for conference_id
if (isset($_REQUEST['cid']))
{
	$content['conference_id'] = $_REQUEST['cid'];
}else{
  echo('Error(m=author&a=new&s=save): no conference_id in "$_REQUEST"');
  exit();
}


//fill content array with values for db

$content['author_id'] = $_SESSION['userID'];

//check for the paper already uploaded (, via reload e.g.)
$SQL = "SELECT id FROM paper WHERE author_id = ". $content['author_id'] ." AND conference_id = ". $content[conference_id];
$result = $sql->query($SQL);
if (count($result) > 0)
{redirect("author", "view", "papers");}

//if okay, proceed
$content['title'] = $_REQUEST['title'];
$content['abstract'] = $_REQUEST['summary'];
$content['last_edited'] = date('Y-m-d');
$content['filename'] = "";
$content['state'] = 0;
$content['mime_type'] = $_REQUEST['file']['type'];


//insert new db entry
$SQL = "INSERT INTO paper (conference_id, author_id, title, abstract, last_edited, filename, state, mime_type) VALUES ('".$content['conference_id']."', '".$content['author_id']."', '".$content['title']."', '".$content['abstract']."', '".$content['last_edited']."', '".$content['filename']."', '".$content['state']."', '".$content['mime_type']."')";
$result = $sql->insert($SQL); 


//get paper_id
$paper_id = mysql_insert_id();

// get fTP handle
$ftphandle=@ftp_connect($ftphost);

// errorhandling, to be improved
if (!$ftphandle) {
	die("Gott ist scheisse! FTP-host unauffindbar!");
	}
// login or give errormessage if login failed
@ftp_login($ftphandle, $ftpuser, $ftppass) or die("FTP user oder pass falsch!");


$ftppath="./" . $ftpdir . "/" . $paper_id;

@ftp_mkdir($ftphandle, $ftppath) or die("ftp_mkdir ".$ftppath." fehlgeschlagen!");

// changedir to $ftpdir ausser config, darunter Verzeichnis $paperid, oder wirf Fehler
@ftp_chdir($ftphandle, $ftppath) or die("FTP-chdir to ".$ftppath." fehlgeschlagen!");

$remotefilename = $paper_id .".". $ending;
$localfilename = $_FILES['file']['tmp_name'];

// put file, auto-creating a filename styled $paperid.$ending with ending coming from mimemap, or die in error
@ftp_put($ftphandle, $remotefilename, $localfilename ,FTP_BINARY) or redirect("author", "new", "cleanup", "pid=".$paper_id);//die("Hochladen nach '$ftppath / $remotefilename' irgendwie fehlgeschlagen. Schade. Aber ich hab noch Erdbeertoertchen...");

$SQL = "UPDATE paper SET filename = '".$remotefilename."' WHERE id = ".$paper_id."";
$change_result = $sql->insert($SQL);

// do not forget to close connection. Your FTP-provider says thanks :)
// ftp_close() throws error, so use here ftp_quit()
@ftp_quit($ftphandle);

redirect("author");
//done at last! Good luck!
?>
