<?

if (!isset ($_REQUEST['pid']))
	{echo "Error (author new cleanup): 'pid' is missing!";}
$pid = $_REQUEST['pid'];
$SQL = "DELETE FROM paper WHERE id = ". $pid;
$result = $sql->insert($SQL);

redirect("author", "new", "form");

?>
