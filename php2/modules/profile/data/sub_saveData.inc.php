<?
$sql = new SQL();
$sql->connect();
if(isset($_SESSION['userID']))
{
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
		$output['email']=strtolower($_POST['email']);
		$output['last_name_error']="";
		$output['email_error']="";
		
		//Evaluate the data
		
		$errorExists=0;		
		
	 	if($output['last_name']=="")
	  	{
		  	$output['last_name_error']="Please fill out your last name!";
			$errorExists=1;		
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
				$SQL = "SELECT id from person 
				        where email = '".$output['email']."'
						AND NOT (id = ".$_SESSION['userID'].")";
				$result=mysql_query($SQL);
			    if ($list = mysql_fetch_row ($result)) 	
			    {				
		        	$output['email_error']="This email already exists!";			
					$errorExists=1;					
				}
			}	  
		}
		$TPL['profile'] = $output;
		if($errorExists==0)  //If no error, update the database
		{				
			$_SESSION['userName'] = $output['first_name']." ".$output['last_name'];
			$SQL = "UPDATE person SET 
					title = '".$output['title']."', 
					first_name = '".$output['first_name']."',
					last_name = '".$output['last_name']."', 
					affiliation = '".$output['affiliation']."', 
					email = '".$output['email']."', 
					phone_number = '".$output['phone']."', 
					fax_number = '".$output['fax']."', 
					street = '".$output['street']."', 
					postal_code = '".$output['postal']."', 
					city = '".$output['city']."', 
					state = '".$output['state']."', 
					country = '".$output['country']."' 
					WHERE id = ".$_SESSION['userID']; 

			$result=mysql_query($SQL); 
			 
			template("PROFILE_dataChanged");	
		}
		else  //if error, do not update and show the errors
		{
			template("PROFILE_showData");			
		}
  }
  else
  {
	template("PROFILE_showData");
  }
}
else redirect("logout",false,false,"error=1");	
?>