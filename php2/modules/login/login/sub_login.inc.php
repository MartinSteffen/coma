<?
if(isset($_SESSION['userID']))
{
	redirect("welcome");
	exit();	
}
else
{
	if(isset($_GET['error']))
	{	
		$error = $_GET['error'];
		if($error == 1)
		{
			$TPL['login'] = "A problem occured with the session. Please login again.";
		}
	}
	
	if(isset($_POST['Submit']) && isset($_POST['email']) && isset($_POST['pass']))
	{
		$pass = makePassword($_POST['pass']); 
	
		$SQL = "SELECT id, first_name, last_name from person where email = '".$_POST['email']."' and password = '".$pass."'";
		$result=mysql_query($SQL);
	    if ($list = mysql_fetch_row ($result)) 	
	    {
			$_SESSION['userID'] = $list[0];
			$_SESSION['userName'] = $list[1]." ".$list[2];
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