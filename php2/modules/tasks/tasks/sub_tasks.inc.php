<?
if(isset($_SESSION['userID']))
{
	/* algorithm for the tasks */
	template("TASKS");
}
}
else redirect("logout","","","mode=1");	
?>