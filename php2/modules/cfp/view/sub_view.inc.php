<?

if (! isset ($_REQUEST['cid'])) {
	redirect();
}
$cid = $_REQUEST['cid'];

$SQL = "SELECT name, description FROM conference WHERE id = " . $cid;
$result = $sql->query($SQL);
$result[0]['description'] = eregi_replace("\n", "<br>", $result[0]['description']);

$TPL = array('conf' => $result[0], 'cid' => $cid);
template("CFP_view_view");
?>
