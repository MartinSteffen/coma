<?
if(isChair_Paper($_GET['paperID']))
{
	$SQL = "select paper.title, paper.abstract, paper.last_edited, paper.version, paper.state, 
	        person.id, person.title, person.first_name, person.last_name, 
			conference.id, conference.name
			from paper, conference, person, role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id 
			and conference.id = paper.conference_id 
			and paper.id = ".$_GET['paperID']." 
			and paper.author_id = person.id";
					
    $result=mysql_query($SQL);
	$output = array();
	$paper = array();
	$count = 0;	
    if ($list = mysql_fetch_row ($result)) 	
	{	
		$paper['paperID'] = $_GET['paperID'];
		$paper['paperName'] = $list[0];
		$paper['paperDesc'] = $list[1];	
		$paper['lastEdited'] = date("j F Y, g:i a" , strtotime($list[2]));
		$paper['version'] = $list[3];
		$paper['stateID'] = $list[4];
		if($list[4]==0)
		{
			$paper['state'] = "Open";
			$paper['class'] = "textGreen";
		}
		if($list[4]==1)
		{
			$paper['state'] = "Being reviewed";
			$paper['class'] = "textGreen";
		}
		if($list[4]==2)
		{
			$paper['state'] = "Being reviewed, conflicting";
			$paper['class'] = "textRed";
		}		
		if($list[4]==3)
		{
			$paper['state'] = "Accepted";
			$paper['class'] = "textGreen";
		}
		if($list[4]==4)
		{
			$paper['state'] = "Rejected";
			$paper['class'] = "textRed";
		}	
		$paper['authorID'] = $list[5];
		$paper['authorName'] = $list[6]." ".$list[7]." ".$list[8];
		$paper['confID'] = $list[9];
		$paper['confName'] = $list[10];
	}
	
$output['paper'] = $paper;

/* HERE COME ALSO THE OTHER STUFF FOR THE PAPER */

$TPL['chair'] = $output;
template("CHAIR_paper");
$TPL['chair'] = "";
}
else redirect("logout");		
?>