<?
  include('templates/header.tpl.php');
  $input = d('errors');
  $topiclist = $TPL['topiclist'];

	if (count($topiclist)==0)
	{
		echo "
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Topic</b></font>
		</legend>
		<table>
		<tr><td>There are no topics you can choose.</td></tr>
		</table>
		</fieldset>";
	} else {
		echo "
		<form method='POST' action='index.php?m=reviewer&a=topic&s=choose'>
		<table align='left' width='100%'><tr><td>
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>My preferred topics</b></font>
		</legend>
		&nbsp;<br>You can choose your preferred topics, and save it with: <input type='submit' value='set' name='B1'><br>&nbsp;
		";

		$conf = NULL;
		foreach ($topiclist as $topic)
		{
			if ($topic['conference']!=$conf)
			{
				$conf=$topic['conference'];
				echo "
				</fieldset>
				<fieldset>
				<legend>
				<font class='textBold' size='+2'><b>".$conf."</b></font>
				</legend>";
			}
			echo "<input type='checkbox' name='C".$topic['conferenceid']."T".$topic['topicid']."' value='1' ".$topic['value'].">".$topic['topicname']."<br>";
		}

		echo "</td></tr></table></form>";
	}
  include('templates/footer.tpl.php');
?>