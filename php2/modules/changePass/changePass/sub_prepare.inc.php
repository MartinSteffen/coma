<?
$sql = new SQL();
$sql->connect();
$output = "";
$errorExists=0;
//check if should update the database
if(isset($_POST['Submit']))
{	
	  	if($_POST['email']=="")
	  	{
	  		$output = "Please enter your email!";
			$errorExists=1;		
	  	}
	  	else
	  	{
	  		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email'])) 
			{ 
	        	$output = "This email is invalid!";
				$errorExists=1;				
    		}  
		}
		$TPL['changePass'] = $output;
		if($errorExists==0)  //If no error, update the database
		{
		   //generate new password
			$newPass = "";
			for($i=0;$i<20;$i++)
			{
				$randomNumber = rand(0,25);				
				$newPass .= strtoupper(chr($randomNumber+97));   
			}
			
			//send email
			$SQL = "SELECT first_name, last_name FROM person WHERE email = '".$_POST['email']."'";
			$result=mysql_query($SQL);
			$toName = "";
   			while ($list = mysql_fetch_row ($result)) 	
		    {
		       $toName = $list[0]." ".$list[1];
		    }
			
			$SQL = "SELECT email FROM person WHERE id = 1";
			$result=mysql_query($SQL);
			$fromName = "Coma";
			$fromEmail = "";
   			while ($list = mysql_fetch_row ($result)) 	
		    {
		       $fromEmail = $list[0];
		    }						
			
			$ToSubject = "Your new password in COMA.";			
			
$Message = "You requested a new password for Coma.

Your new password is $newPass

The password is random generated. The old password is deleted.

Please login with this password and change it under My Profile.

With best regards,
Coma

PS: This is an automatic generated email. Please do not reply.";			
			
			mail($ToName." <".$_POST['email'].">",$ToSubject, $Message, "From: ".$fromName." <".$fromEmail.">");
			
//	mail("ivan <ivan@stragalis.de>","test", "test message", "From: bla <bla@bla.com>");
	
			//Update the database
			$SQL = "UPDATE person SET password='".makePassword($newPass)."' WHERE email='".$_POST['email']."'";
			$result=mysql_query($SQL);

			template("CHANGEPASS_ok");	
		}
		else  //if error, do not update and show the errors
		{
			template("CHANGEPASS_prepare");			
		}
}
else
{
	template("CHANGEPASS_prepare");
}



?>