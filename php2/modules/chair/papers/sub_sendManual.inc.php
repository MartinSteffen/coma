<?
$sql = new SQL();
$sql->connect();
if(isChair_Paper($_POST['paperID']))
{	
	if (isset($_POST['Submit']) && isset($_POST['reviewers']))
	{		
		foreach ($_POST['reviewers'] as $reviewer)
		{
			$SQL = "INSERT INTO reviewreport (reviewer_id, paper_id) 
					VALUES (".$reviewer.", ".$_POST['paperID'].")";
			$result=mysql_query($SQL);					
		}
		makePaperState($_POST['paperID']);
	}

	redirect("chair","papers","paper","paperID=".$_POST['paperID']);	

}
else redirect("logout",false,false,"error=1");	
?>