<?
if(isChair_Paper($_GET['paperID']))
{
	// call the PTRA Algorithm (Gunnar)
	redirect("chair","papers","paper","paperID=".$_GET['paperID']);
}
else redirect("logout",false,false,"error=1");	
?>


