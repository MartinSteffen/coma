<?
	$link = mysql_connect ("localhost","testUser","testPass");
	$base = mysql_select_db ("testComma");
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
/* DEBUG START 
$conferences[] = array("id"=>1, "name"=>"Conference A", "desc"=>"Desc A");
$conferences[] = array("id"=>2, "name"=>"Conference B", "desc"=>"Desc B");
 DEBUG END */

$TPL['chair'] = $conferences;
template("CHAIR_chooseConf");
$TPL['chair'] = "";
?>