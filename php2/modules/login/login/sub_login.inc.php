<?
	$error = $_GET['error'];
	if(isset($_SESSION['userID']))
	{
		redirect("welcomeUser");
		exit();	
	}
	else
	{
		if(isset($_POST['Submit']) && isset($_POST['email']) && isset($_POST['pass']))
		{
			$pass = makePassword($_POST['pass']); 
		
			$SQL = "SELECT id, first_name, last_name from person where email = '".$_POST['email']."' and password = '".$pass."'";
			$result=mysql_query($SQL); 
		    if ($list = mysql_fetch_row ($result)) 	
			{
				$_SESSION['userID'] = $list[0];
				$_SESSION['userName'] = $list[1]." ".$list[2];
				redirect("welcomeUser");
				exit();		   
		    }	
			else
			{
				$error = 2;
			}
		}
		if($error == 0)
		{
			$TPL['login'] = "";
		}
		if($error == 1)
	    {
			$TPL['login'] = "A problem occured with the session. Please login again.";
		}	
		if($error == 2)
	    {
			$TPL['login'] = "Wrong email or password.";
		}	
		template("LOGIN");
	}	
?>	