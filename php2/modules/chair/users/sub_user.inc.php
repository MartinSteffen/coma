<?
if(isChair_Person($_GET['personID']))
{
	$SQL = "SELECT title, first_name, last_name, affiliation, email, phone_number,
			fax_number, street, postal_code, city, state, country
			FROM person
			WHERE id = ".$_GET['personID'];
    $result=mysql_query($SQL);
    $person = array();
    while ($list = mysql_fetch_row ($result)) 	
    {
       $person['personID'] = $_GET['personID'];
	   $person['title'] = $list[0];
	   $person['first_name'] = $list[1];
	   $person['last_name'] = $list[2];
	   $person['affiliation'] = $list[3];
	   $person['email'] = $list[4];
	   $person['phone_number'] = $list[5];
	   $person['fax_number'] = $list[6];
	   $person['street'] = $list[7];
	   $person['postal_code'] = $list[8];
	   $person['city'] = $list[9];
	   $person['state'] = $list[10];
	   $person['country'] = $list[11];	   
    }
	
	
	//Get the roles
/*	$SQL = "SELECT id, name from topic WHERE conference_id = ".$_GET['confID'];
    $result=mysql_query($SQL);
    $topics = array();
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
    {
		$topic = array();
		$topic['topicID'] = $list[0];
		$topic['topicName'] = $list[1];
		$topics[$count] = $topic;
		$count++;
	} */
	
		
	$output = array();
	$output['person'] = $person;				
//	$output['topics'] = $topics;

$TPL['chair'] = $output;
template("CHAIR_user");
}
else redirect("logout",false,false,"error=1");	
?>