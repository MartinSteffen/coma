<? 

$sql = new SQL();
$sql->connect();
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
	for($j=0;$j<count($output);$j++){
		$temp = $sql->query("SELECT person_id FROM role WHERE conference_id='{$output[$j]['id']}' AND role_type='2'");
		$result[$j]=$temp[0];
	}
	for($i=0;$i<count($result);$i++){
		$chairid = $result[$i]['person_id'];
		$chair = $sql->queryAssoc("SELECT first_name, last_name, email FROM person WHERE id='$chairid'");
		$output[$i]['chairname'] = $chair['0']['firstname']." ".$chair['0']['last_name'];
		$output[$i]['chairemail'] =$chair['0']['email'];
	}
	$TPL['ADMIN_colnames'] = $colnames;
	$TPL['ADMIN_conferenceList'] = $output;		
	template("ADMIN_conferenceList");
}
else redirect("logout",false,false,"error=1");	
?>
