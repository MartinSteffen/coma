<?

if (isset ($_REQUEST['pid']) and ($_REQUEST['pid'] != "")) {
	$paper_id = $_REQUEST['pid'];
}else{
	redirect("author", "view", "papers", "msg=<font class=textBold style=color:red>Error (author_view_getfile): pid is missing!");
}


$SQL = "SELECT filename, mime_type FROM paper WHERE id = ".$paper_id;
$result = $sql->query($SQL);

$ftppath = "/". $ftpdir . "/"  . $paper_id ."/".$result[0]['filename'];



$ftphandle = ftp_connect($ftphost);
if (!$ftphandle) {
	redirect("author", "view", "papers", "msg=<font class=textBold style=color:red>Error (author_view_getfile): ftp_connect() failed!");
}
ftp_login($ftphandle, $ftpuser, $ftppass) or die("Error (author_view_getfile): ftp_login() failed!");

$size = ftp_size($ftphandle, $ftppath);

if (!$file = fopen("ftp://" . $ftpuser. ":" . $ftppass . "@" . $ftphost."". $ftppath, "r")) {
	var_dump($file);
	exit();
}else{	

	header ("Content-Type: " . $result[0]['mime_type']);
	header ("Content-Length: $size");
	header ("Content-Disposition: attachment; filename=" . $result[0]['filename']);
	fpassthru($file);
}
//dieser redirect wird aus irgendeinem grund nicht ausgegeben
redirect("author", "view", "papers", "msg=<font class=textBold style=color:green>Download finished succssesfully!</font>");

?>
