<?
$sql = new SQL();
$sql->connect();

	$error = 0;
	if (isset($_GET['error']))
	{
		$error = $_GET['error'];
	}
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
			$email = strtolower($_POST['email']);
			$SQL = "SELECT id, first_name, last_name from person where email = '".$email."' and password = '".$pass."'";
			$result=$SQL->query($SQL); 
		    if ($list = $result[0]) 	
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
