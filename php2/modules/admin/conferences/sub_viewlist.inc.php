<? 

include("includes/rtp.lib.php");
if(isAdmin_Overall() 
  || 1
)
{

//TODO:
//process inputs

	$colnames=$sql->queryAssoc("SHOW COLUMNS FROM conference");
	$orderby="name";
	if (isset($HTTP_POST_VARS['orderby'])) {
		$orderby=$HTTP_POST_VARS['orderby'];
	}
	$output = $sql->queryAssoc("SELECT * FROM conference ORDER BY ".$orderby);

	$TPL['ADMIN_colnames'] = $colnames;
	$TPL['ADMIN_conferenceList'] = $output;		
	template("ADMIN_conferenceList");
}
else redirect("logout",false,false,"error=1");	
?>