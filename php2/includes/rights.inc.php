<?

/* ----- FOR CHAIR ------------------------------------------------------ */
function isChair_Paper($paperID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($paperID)) && (!($paperID == "")))
  {
	$SQL = "select role.person_id from paper, role
			where role.role_type = 2
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id
			and paper.id = ".$paperID;

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isChair_Conference($confID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($confID)) && (!($confID == "")))
  {
	$SQL = "select role.person_id from role
			where role.role_type = 2
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID;

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isChair_Topic($topicID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($topicID)) && (!($topicID == "")))
  {
	$SQL = "select role.person_id from role, topic
			where role.role_type = 2
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = topic.conference_id
			and topic.id = ".$topicID;

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isChair_Person($userID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($userID)) && (!($userID == "")))
  {
	$SQL = "select X.person_id
			from role X, role Y
			where X.role_type = 2
			and X.state = 1
			and X.person_id = ".$_SESSION['userID']."
			and X.conference_id = Y.conference_id
			and Y.person_id = ".$userID;
    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isChair_Overall()
{

	global $sql;
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role
			where role.role_type = 2
			and role.state = 1
			and role.person_id = ".$_SESSION['userID'];

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}


/* ----- FOR ADMIN ------------------------------------------------------ */

function isAdmin_Overall()
{

  if(isset($_SESSION['userID']))
  {
	return $_SESSION['userID']==1;
  }
  return false;
}


/* ----- FOR AUTHOR ------------------------------------------------------ */
function isAuthor_Paper($paperID)
{


  global $sql;
if ((isset($_SESSION['userID'])) && (isset($paperID)) && (!($paperID == "")))
  {
	$SQL = "select role.person_id from paper, role
			where role.role_type = 4
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id
			and paper.id = ".$paperID;

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isAuthor_Conference($confID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($confID)) && (!($confID == "")))
  {
	$SQL = "select role.person_id from role
			where role.role_type = 4
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID;

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isAuthor_Overall()
{
	global $sql;
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role
			where role.role_type = 4
			and role.state = 1
			and role.person_id = ".$_SESSION['userID'];

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}


/* ----- FOR REVIEWER ------------------------------------------------------ */
function isReviewer_Paper($paperID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($paperID)) && (!($paperID == "")))
  {
	$SQL = "select role.person_id from paper, role
			where role.role_type = 3
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id
			and paper.id = ".$paperID;

	// Das ist wohl ein bug ;-)

	$SQL = "SELECT paper_id, reviewer_id FROM reviewreport WHERE (reviewer_id = ".$_SESSION['userID'].") AND (paper_id = ".$paperID.")";

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isReviewer_Conference($confID)
{
  global $sql;
if ((isset($_SESSION['userID'])) && (isset($confID)) && (!($confID == "")))
  {
	$SQL = "select role.person_id from role
			where role.role_type = 3
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = ".$confID;

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

function isReviewer_Overall()
{
	global $sql;
  if(isset($_SESSION['userID']))
  {
	$SQL = "select role.person_id from role
			where role.role_type = 3
			and role.state = 1
			and role.person_id = ".$_SESSION['userID'];

    $result=$sql->query($SQL);
    if ($result)
	{
		return true;
	}
  }
  return false;
}

?>