<?
$sql = new SQL();
$sql->connect();


if (isset ($_REQUEST['cid']))
{
  $TPL['cid'] = $_REQUEST['cid'];
}else{
  echo ('Error: no conference_id!');
}
  template("AUTHOR_new_form");
?>
