<?

// ich brauch als Variablen, die vorher gesetzt werden:

// $mimemap: assoziatives Array, das Mime-types auf Endungen mappt.
// also im Stil von:


// var_dump($_REQUEST);
// var_dump($_POST);
// var_dump($_GET);
// var_dump($_SESSION);
// var_dump($_FILES);
// echo date('Y-m-d');
// exit();

$mimemap= array(
	"application/pdf" => ".pdf",
	"application/postscript" => ".ps",
	"application/msword" => ".doc",
	"aplication/x-dvi" => ".dvi",
	"text/plain" => ".txt"
);

$content = array();

if (isset($_REQUEST['cid']))
{
	$content['conference_id'] = $_REQUEST['cid'];
}else{
  echo('Error(m=author&a=new&s=save): no conference_id in "$_REQUEST"');
  exit();
}

$content['author_id'] = $_SESSION['userID'];
$content['title'] = $_REQUEST['title'];
$content['abstract'] = $_REQUEST['summary'];
$content['last_edited'] = date('Y-m-d');
$content['filename'] = "";
$content['state'] = 0;
$content['mime_type'] = $_REQUEST['file']['type'];

// var_dump($content);
// exit();



$SQL = "INSERT INTO paper (conference_id, author_id, title, abstract, last_edited, filename, state, mime_type) VALUES ('".$content['conference_id']."', '".$content['author_id']."', '".$content['title']."', '".$content['abstract']."', '".$content['last_edited']."', '".$content['filename']."', '".$content['state']."', '".$content['mime_type']."')";
$result = mysql_query($SQL); //Warum wirft er hier Fehlermeldung wenn man "$sql->query" benutzt??
// var_dump($result);


$paper_id = mysql_insert_id();
// var_dump($paper_id);
// exit();



// $paperid: die aktuelle paperid. 'select max(id) from paper'+1 oder $sql->insertid(); nach dem Einfuegen des Paper-Datensatzes

// $_FILES sollte automatisch gefuellt sein, wenn das Template-Formular auf dieses Script zeigt...


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

$remotefilename = $paper_id . $mimemap[ $_FILES['file']['type'] ];
$localfilename = $_FILES['file']['tmp_name'];

// put file, auto-creating a filename styled $paperid.$ending with ending coming from mimemap, or die in error
@ftp_put($ftphandle, $remotefilename, $localfilename ,FTP_BINARY) or die("Hochladen nach '$ftppath / $remotefilename' irgendwie fehlgeschlagen. Schade. Aber ich hab noch Erdbeertoertchen...");

$SQL = "UPDATE paper SET filename = '".$remotefilename."' WHERE id = ".$paper_id."";
$change_result = mysql_query($SQL);
// var_dump($change_result);

// do not forget to close connection. Your FTP-provider says thanks :)
@ftp_close($ftphandle);

redirect("author");
// Irgendwie macht er ab hier nix mehr

echo 'Hallo Welt?';


echo 'Hallo Welt!';
//done at last! Good luck!
?>
