<?
include 'security.php';
include 'templates/header.php';
include 'templates/upper-links.php';

//Initialise variables
$title="";
$first_name="";
$last_name="";
$afiliation="";
$street="";
$postal_code="";
$city="";
$country="";
$state="";
$phone="";
$fax="";
$email="";
$colorHint="#006600";
$hint="";
$error = array();
$error['first_name']="";
$error['last_name']="";
$error['email']="";

//check if should update the database
if(isset($_POST['Submit']))
{
		$title=$_POST['title'];
		$first_name=$_POST['first_name'];
		$last_name=$_POST['last_name'];
		$afiliation=$_POST['afiliation'];
		$street=$_POST['street'];
		$postal_code=$_POST['postal_code'];
		$city=$_POST['city'];
		$country=$_POST['country'];
		$state=$_POST['state'];
		$phone=$_POST['phone'];
		$fax=$_POST['fax'];
		$email=$_POST['email'];

		//Evaluate the data
		
		$errorExists=0;		
								  
	  	if($first_name=="")
	  	{
	  		$error['first_name']="Please fill out your first name!";			
			$errorExists=1;				
	  	}
	 	if($last_name=="")
	  	{
		  	$error['last_name']="Please fill out your last name!";
			$errorExists=1;		
	  	}
	  	if($email=="")
	  	{
	  		$error['email']="Please fill out your email!";		
			$errorExists=1;
	  	}
	  	else
	  	{
	  		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) 
			{ 
	        	$error['email']="This email is invalid!";			
				$errorExists=1;
    		}
		}	  
		
		if($errorExists==0)  //If no error, update the database
		{
			//UPDATE DATABASE
			$colorHint="#006600";
			$hint="Your properties are saved.";
		}
		else  //if error, do not update and show the error hint
		{
			$colorHint="#990000";
			$hint="Your properties are not saved.";			
		}
}
else
{
	//SELECT FROM DATABASE
}
include 'templates/user-attr.php';
include 'templates/footer.php';
?>