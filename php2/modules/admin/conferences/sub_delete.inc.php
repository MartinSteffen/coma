<? 
$sql = new SQL();
$sql->connect();

if(isAdmin_Overall()){

	$cid=$_REQUEST['cid'];
	if (!$cid) $cid="0";

	$query="SELECT id FROM paper WHERE conference_id=$cid";
	$result=$sql->queryAssoc($query);
	foreach($result as $row) {
		cleanup_ftp( $row['id'] );
	}

	$query="DELETE FROM conference WHERE id=$cid";
	$result=$sql->insert($query);
	
//	if (!$result) freu_drüber( );

	redirect("admin","conferences",false, false);

}else{ 
	redirect("logout",false,false,"error=1");	
}
?>
