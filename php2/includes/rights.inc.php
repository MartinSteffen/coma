<?

/* ----- FOR CHAIR ------------------------------------------------------ */
function isChair_User_Paper($paperID)
{
  if($_SESSION['userID'])
  {
	$SQL = "select role.person_id from paper, role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id 
			and paper.id = ".$paperID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isChair_User_Conference($confID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isChair_Overall()
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}


/* ----- FOR ADMIN ------------------------------------------------------ */
function isAdmin_User_Paper($paperID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from paper, role 
			where role.role_type = 1
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id 
			and paper.id = ".$paperID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isAdmin_User_Conference($confID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 1
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isAdmin_Overall()
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 1
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}


/* ----- FOR AUTHOR ------------------------------------------------------ */
function isAuthor_User_Paper($paperID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from paper, role 
			where role.role_type = 4
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id 
			and paper.id = ".$paperID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isAuthor_User_Conference($confID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 4
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isAuthor_Overall()
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 4
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}


/* ----- FOR REVIEWER ------------------------------------------------------ */
function isReviewer_User_Paper($paperID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from paper, role 
			where role.role_type = 3
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id 
			and paper.id = ".$paperID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isReviewer_User_Conference($confID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 3
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isReviewer_Overall()
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role 
			where role.role_type = 3
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

?>