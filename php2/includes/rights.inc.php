<?

/* ----- FOR CHAIR ------------------------------------------------------ */
function isChair_Paper($paperID)
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

function isChair_Conference($confID)
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

function isChair_Topic($topicID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role, topic 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = topic.conference_id
			and topic.id = ".$topicID; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		return true;
	}
  }
  return false;
}

function isChair_Person($userID)
{
  if(isset($_SESSION['userID']))
  {
	$SQL = "select X.person_id
			from role X, role Y  
			where X.role_type = 2
			and X.state = 1 
			and X.person_id = ".$_SESSION['userID']."
			and X.conference_id = Y.conference_id
			and Y.person_id = ".$userID;
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
function isAdmin_Paper($paperID)
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

function isAdmin_Conference($confID)
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
function isAuthor_Paper($paperID)
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

function isAuthor_Conference($confID)
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
function isReviewer_Paper($paperID)
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

function isReviewer_Conference($confID)
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