<?

function loginas($role){
	$_SESSION['role'] = $role;
	$_SESSION['user'] = "Gast";
	$_SESSION['id'] = "000000";
	$_SESSION['name'] = "Gast";
	}
	
function check_login(){
	// {{{
	global $config;
	$eingeloggt = false;

	//checklogin wird noch nicht gebraucht
	if (false){
	if (isset($_SESSION['eingeloggt']) && !isset($_GET['logout'])){
		$eingeloggt = $_SESSION['eingeloggt'];
	}elseif(isset($_GET['logout'])){
		$eingeloggt = false;
		$_SESSION['user'] = "Gast";
		$_SESSION['id'] = "000000";
		$_SESSION['name'] = "Gast";
		$_SESSION['timeout'] = false;
		$_SESSION['message'] = null;
		session_destroy();
		redirect();
	}elseif (isset($_SESSION['timeout'])){
		$eingeloggt = false;
	}else{
		$eingeloggt = false;
		$_SESSION['user'] = "Gast";
		$_SESSION['pnr'] = "000000";
		$_SESSION['name'] = "Gast";
	}
	if ($eingeloggt && $_SESSION['tolo'] < time()){
		$eingeloggt = false;
		$_SESSION['eingeloggt'] = false;
		$_SESSION['timeout'] = true;
		$_SESSION['message'] = "Sie haben die Seite längere Zeit nicht verwendet. <b>Aus Sicherheitsgründen müssen Sie sich daher erneut einloggen.</b> Um sich mit unter einer anderen Windowskennung anzumelden, müssen Sie sich zunächst <a href=\"index.php?logout=1\">ausloggen</a>.";
	}
	if ($eingeloggt){
		if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] || $_SESSION['browser'] != $_SERVER['HTTP_USER_AGENT']){
			$eingeloggt = false;
			$_SESSION['eingeloggt'] = false;
		}
	}
	if ($eingeloggt)	{
		$_SESSION['tolo'] = time() + $config['timeout'];
	}
	return $eingeloggt;
	}else{
		return true;
	}
	// }}}
}


function login($error)
{   
	global $TPL;

	if(isset($_SESSION['userID']))
	{
		redirect("welcome");
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
				redirect("welcome");
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
}

function logout($error)
{
	//Destroy the session
	session_unset(); 
	session_destroy(); 
	
	if($error == 1)
	{
		redirect(false,false,false,"login=1");
	}
	if($error == 0)
	{
		redirect(false,false,false,"login=0");
	}
}

function register()
{
global $TPL;
$output = array();

//check if should update the database
if(isset($_POST['Submit']))
{
		$output['title']=$_POST['title'];
		$output['first_name']=$_POST['first_name'];
		$output['last_name']=$_POST['last_name'];
		$output['affiliation']=$_POST['affiliation'];
		$output['street']=$_POST['street'];
		$output['postal']=$_POST['postal'];
		$output['city']=$_POST['city'];
		$output['country']=$_POST['country'];
		$output['state']=$_POST['state'];
		$output['phone']=$_POST['phone'];
		$output['fax']=$_POST['fax'];
		$output['email']=$_POST['email'];
		$output['pass']=$_POST['pass'];
		$output['passRetype']=$_POST['passRetype'];

		//Evaluate the data
		
		$errorExists=0;		
		
	 	if($output['last_name']=="")
	  	{
		  	$output['last_name_error']="Please fill out your last name!";
			$errorExists=1;		
	  	}
	 	if($output['pass']=="")
	  	{
		  	$output['pass_error']="Please enter a password!";
			$errorExists=1;		
	  	}
		else
		{
			if(!($output['pass'] == $output['passRetype']))						  
			{
			  	$output['pass_error']="Error in password verification!";
				$errorExists=1;			
			}		
		}		
	  	if($output['email']=="")
	  	{
	  		$output['email_error']="Please enter your email!";		
			$errorExists=1;
	  	}
	  	else
	  	{
	  		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $output['email'])) 
			{ 
	        	$output['email_error']="This email is invalid!";			
				$errorExists=1;
    		}
			else
			{
				$SQL = "SELECT id from person where email = '".$output['email']."'";
				$result=mysql_query($SQL);
			    if ($list = mysql_fetch_row ($result)) 	
			    {				
		        	$output['email_error']="This email already exists!";			
					$errorExists=1;					
				}
			}	  
		}
		$TPL['register'] = $output;
		if($errorExists==0)  //If no error, update the database
		{				
			$pass = makePassword($output['pass']);
			
			$SQL = "INSERT INTO person (title, first_name, last_name, affiliation, email, phone_number, fax_number, street, postal_code, city, state, country, password) 
				    VALUES ('".$output['title']."', '".$output['first_name']."', '".$output['last_name']."', '".$output['affiliation']."', '".$output['email']."', '".$output['phone']."', '".$output['fax']."', '".$output['street']."', '".$output['postal']."', '".$output['city']."', '".$output['state']."', '".$output['country']."', '".$pass."')";

			$result=mysql_query($SQL);
			 
			template("REGISTER_ok");	
		}
		else  //if error, do not update and show the errors
		{
			template("REGISTER_showForm");			
		}
}
else
{
	template("REGISTER_showForm");
}
}
