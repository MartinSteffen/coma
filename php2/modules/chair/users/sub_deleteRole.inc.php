<?
if(isChair_Person($_POST['personID']))
{
if(isChair_Conference($_POST['confID']))
{
if(!($_POST['roleType'] == 1))
{
	$SQL = "DELETE FROM role 
		    WHERE person_id = ".$_POST['personID']."
			AND conference_id = ".$_POST['confID']."
			AND role_type = ".$_POST['roleType'];		
	$result=mysql_query($SQL);	

	redirect("chair","users","user","personID=".$_POST['personID']);
}
else redirect("logout",false,false,"error=1");	
}
else redirect("logout",false,false,"error=1");	
}
else redirect("logout",false,false,"error=1");	
?>
	