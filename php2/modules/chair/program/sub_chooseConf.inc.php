<?
$sql = new SQL();
$sql->connect();
if(isChair_Overall())
{
	$SQL = "select conference.id, conference.name, conference.description from conference, role 
            where conference.id = role.conference_id 
			and role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID'];
   $result=mysql_query($SQL);
   $conferences = array();
   while ($list = mysql_fetch_row ($result)) 	
   {
      $conferences[] = array("id"=>$list[0], "name"=>$list[1], "desc"=>$list[2]);   
   }

	if (count($conferences)==1) {
		redirect("chair","program","paperlist","confID=".$conferences[0]['id']);
	}

$TPL['chair'] = $conferences;
template("CHAIR_chooseConf");
$TPL['chair'] = "";
}
else redirect("logout",false,false,"error=1");	
?>