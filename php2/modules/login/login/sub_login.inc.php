<?
if(isset($_SESSION['userID']))
{
	redirect("tasks");
	exit();	
}
else
{
	if(isset($_POST['Submit']) && isset($_POST['email']) && isset($_POST['pass']))
	{
		$pass = makePassword($_POST['pass']); 
	
		$SQL = "SELECT id from person where email = '".$_POST['email']."' and password = '".$pass."'";
		$result=mysql_query($SQL);
	    if ($list = mysql_fetch_row ($result)) 	
	    {
			$_SESSION['userID'] = $list[0];
			redirect();
			exit();		   
	    }	
		else
		{
			$TPL['login'] = "Wrong email or password.";
		}
	}
	template("LOGIN");
}
?>