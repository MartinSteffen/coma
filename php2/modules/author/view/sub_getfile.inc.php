<?

if (! isset ($_REQUEST['pid']) or ($_REQUEST['pid'] == "")) {
  redirect("author", "view", "papers", "msg=Error (author_view_getfile): pid is missing!");
}
else {
  $paperid = $_REQUEST['pid'];
}


if (! $ftphandle = ftp_connect($ftphost)) {
  redirect("author", "view", "papers", "msg=Error (author_view_getfile): ftp_connect failed!");
}

if (! ftp_login($ftphandle, $ftpuser, $ftppass)) {
  redirect("author", "view", "papers", "msg=Error (author_view_getfile): ftp_login failed!");
}

$SQL = "SELECT filename, mime_type FROM paper WHERE id = " . $paperid;
$result = $sql->query($SQL);

$filename = $result[0]['filename'];
$mime_type = $result[0]['mime_type'];

$ftpfile = "./" . $ftpdir . "/" . $paperid . "/" . $filename;


$file = tmpfile();
ftp_fget($ftphandle, $file, $ftpfile, FTP_BINARY);
rewind($file);

header("Content-Type: " . $mime_type . ";");
if ($size = ftp_size($ftphandle, $ftpfile)) {
  header("Content-Length: " . $size . ";");
}
header("Content-Disposition: attachment; filename=" . $filename);
fpassthru($file);
