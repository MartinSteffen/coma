<?
$sql = new SQL();
$sql->connect();


if (isset ($_REQUEST['cid']))
{
// if cid is set, get conference_name from db
  $TPL['cid'] = $_REQUEST['cid'];
	$SQL = "SELECT name FROM conference WHERE id = ".$TPL['cid'];
	$result = $sql->query($SQL);
	$TPL['name'] = $result[0]['name'];
}else{
  echo ('Error: no conference_id!');
}

if (isset ($_REQUEST['msg'])) {
	$msg = $_REQUEST['msg'];
	$TPL['msg'] = $msg;
}


  template("templates/AUTHOR_new_form");
?>
