<?
$output = array();

if (isset ($_REQUEST['TPLdata'])) {
	$TPL = unserialize (urldecode ($_POST['TPLdata']));
	if ($_REQUEST['createNewAccount'] == "no")
	{
		// dump($TPL);
		$SQL = "INSERT INTO role (conference_id, person_id, role_type, state) VALUES ('" . d('cid') . "', '" . d('pid') . "', '4', '1')";
		$result = $sql->insert($SQL);
		// dump($result);
		template("REGISTER_ok");
		exit();
	}
	else
	{
		template("REGISTER_showForm");
		exit();
	}
}


//check for conference

if (! isset ($_REQUEST['cid'])) {
	redirect();
}
$cid = $_REQUEST['cid'];

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
		$output['pass']=$_POST['pass'];
		$output['passRetype']=$_POST['passRetype'];
		$output['last_name_error']="";
		$output['pass_error']="";
		$output['email_error']="";
		
		//Evaluate the data
		
		$errorExists=0;		
		
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
			    	$TPL['register'] = $output;
		    		$TPL['pid'] = $list[0];
				$TPL['cid'] = $cid;
				template("CFP_register_known");
				exit();
		        	// redirect("cfp", "register", "known", "pid=" . $list[0] . "&cid=" . $cid);					
				}
			}	  
		}
		
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
	  	
		$TPL['register'] = $output;
		if($errorExists==0)  //If no error, update the database
		{				
			$pass = makePassword($output['pass']);
			$output['email'] = strtolower($output['email']);
			$SQL = "INSERT INTO person (title, first_name, last_name, affiliation, email, phone_number, fax_number, street, postal_code, city, state, country, password) 
				    VALUES ('".$output['title']."', '".$output['first_name']."', '".$output['last_name']."', '".$output['affiliation']."', '".$output['email']."', '".$output['phone']."', '".$output['fax']."', '".$output['street']."', '".$output['postal']."', '".$output['city']."', '".$output['state']."', '".$output['country']."', '".$pass."')";

			$result=mysql_query($SQL);

			// get used person_id
			$person_id = mysql_insert_id();
			$SQL = "INSERT INTO role (conference_id, person_id, role_type, state) VALUES ('" . $cid . "', '" . $person_id . "', '4', '1')";
			$result = $sql->insert($SQL);
			 
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
?>

