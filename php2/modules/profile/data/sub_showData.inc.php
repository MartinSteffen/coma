<?
if(isset($_SESSION['userID']))
{
	$output = array();
	$SQL = "SELECT title, first_name, last_name, email, affiliation, street, postal_code, city, state, country, phone_number, fax_number
		    FROM person WHERE id = ".$_SESSION['userID'];
	$result=mysql_query($SQL);
	if ($list = mysql_fetch_row ($result)) 	
	{
		$output['title'] = $list[0];
		$output['first_name'] = $list[1];
		$output['last_name'] = $list[2];
		$output['email'] = $list[3];
		$output['affiliation'] = $list[4];
		$output['street'] = $list[5];
		$output['postal'] = $list[6];				
		$output['city'] = $list[7];
		$output['state'] = $list[8];
		$output['country'] = $list[9];
		$output['phone'] = $list[10];
		$output['fax'] = $list[11];
	}
	$TPL['profile'] = $output;
	template("PROFILE_showData");
}
else redirect("logout","","","mode=1");		

?>