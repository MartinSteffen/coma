<?
$sql = new SQL();
$sql->connect();
if(isChair_Person($_GET['userID']))
{
	$SQL = "select conference.id, conference.name from conference, role 
            where conference.id = role.conference_id 
			and role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID'];
   $result=mysql_query($SQL);
   $conferences = array();
   while ($list = mysql_fetch_row ($result)) 	
   {
      $conferences[] = array("confID"=>$list[0], "confName"=>$list[1]);   
   }
$output['conferences'] = $conferences;
$output['userID'] = $_GET['userID'];
$TPL['chair'] = $output;
template("CHAIR_inviteUser");
}
else redirect("logout",false,false,"error=1");	
?>