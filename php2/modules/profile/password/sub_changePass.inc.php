<?
if(isset($_SESSION['userID']))
{
  $output = array();

  //check if should update the database
  if(isset($_POST['Submit']))
  {
		$output['oldPass']=$_POST['oldPass'];
		$output['pass']=$_POST['pass'];
		$output['passRetype']=$_POST['passRetype'];

		//Evaluate the data
		
		$errorExists=0;		
		
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
			else
			{ 
				$pass = makePassword($output['oldPass']);
				$SQL = "SELECT id from person WHERE id = ".$_SESSION['userID']." AND password = '".$pass."'";
				$result=mysql_query($SQL);
	   		    if ($list = mysql_fetch_row ($result)) 	
	    		{				
				}
				else
				{
				  	$output['pass_error']="The current password is wrong!";
					$errorExists=1;						
				}		
			}
		}		

		$TPL['profile'] = $output;
		if($errorExists==0)  //If no error, update the database
		{							
			$pass = makePassword($output['pass']);
			$SQL = "UPDATE person 
				   SET password = '".$pass."' 
				   WHERE id = ".$_SESSION['userID'];

			$result=mysql_query($SQL);
			 
			template("PROFILE_passChanged");	
		}
		else  //if error, do not update and show the errors
		{
			template("PROFILE_changePass");			
		}
  }
  else
  {
   	template("PROFILE_changePass");
  }
}
else redirect("logout","","","mode=1");		

?>