<?
if(isChair_Paper($_GET['paperID']))
{
	/* call the PTRA Algorithm */	
	redirect("chair","papers","paper","paperID=".$_GET['paperID']);
}
else redirect("","","","logout=1");	
?>


