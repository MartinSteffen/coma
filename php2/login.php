<?

	session_start();
	
	//Initialise variables
	$hint="";
	
	//Check the userName and userPass	
	if(isset($_POST['Submit']))
	{
		//DEBUG TEST------------------------------------------	
		if($_POST['userName']=="ivan" && $_POST['userPassword']=="ivan")
		{
			$_SESSION['userID']=1234;
			$_SESSION['userName']=$_POST['userName'];
			$_SESSION['userPassword']=$_POST['userPassword'];
			header("Location: index.php");
			exit;		
		}
		else
		{
			$hint="Wrong user name or password. Please try again.";		
		}
		//END DEBUG TEST--------------------------------------	
	}
	
	include 'templates/header.php';
	include 'templates/login.php';
	include 'templates/footer.php';	
?>