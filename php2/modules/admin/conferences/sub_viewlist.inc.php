<? 

include("includes/sql.class.php");
$sql = new SQL();
if(isAdmin_Overall())
{

//TODO:
//process inputs

	$colnames=array(
		"Name",
		"Description",
		"No of reviews per paper",
		"Conference start",
		"Conference end"
	);
	$orderby="name";
	if (isset($HTTP_POST_VARS['orderby'])) {
		$orderby=$HTTP_POST_VARS['orderby'];
	}
	$output = $sql->queryAssoc("SELECT id, name, description, min_reviews_per_paper, conference_start, conference_end FROM conference ORDER BY ".$orderby);

	$TPL['ADMIN_colnames'] = $colnames;
	$TPL['ADMIN_conferenceList'] = $output;		
	template("ADMIN_conferenceList");
}
else redirect("logout",false,false,"error=1");	
?>