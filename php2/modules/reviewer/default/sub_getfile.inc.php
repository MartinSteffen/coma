<?

if (! isset ($_GET['pid']) or ($_GET['pid'] == "")) {
  redirect("reviewer", "error", false, "msg=Paper-ID is missing!");
}
else {
  $paperid = $_GET['pid'];
}


if (! $ftphandle = ftp_connect($ftphost)) {
  redirect("reviewer", "error", false, "msg=ftp_connect failed!");
}

if (! ftp_login($ftphandle, $ftpuser, $ftppass)) {
  redirect("reviewer", "error", false, "msg=ftp_login failed!");
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
