<?

// ich brauch als Variablen, die vorher gesetzt werden:

// $mimemap: assoziatives Array, das Mime-types auf Endungen mappt.
// also im Stil von:

$mimemap= array(
	"application/pdf" => ".pdf"
);

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

// changedir to $ftpdir ausser config, darunter Verzeichnis $paperid, oder wirf Fehler
@ftp_chdir($ftphandle, $ftpdir."/".$paperid) or die("FTP-chdir to ".$ftpdir."/".$paperid." fehlgeschlagen!");

// put file, auto-creating a filename styled $paperid.$ending with ending coming from mimemap, or die in error
@ftp_put($ftphandle, $paperid.$mimemap[$_FILES['file']['type']], $_FILES['file']['tmp_name'],FTP_BINARY) or die("Hochladen irgendwie fehlgeschlagen. Schade. Aber ich hab noch Erdbeertoertchen...");

// do not forget to close connection. Your FTP-provider says thanks :)
@ftp_close($ftphandle);

//done at last! Good luck!
?>