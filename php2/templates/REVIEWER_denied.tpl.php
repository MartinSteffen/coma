<?
  include('templates/header.tpl.php');
  $input = d("errors");
  $paperlist = $TPL['paperlist'];

	if (count($paperlist)==0)
	{
		echo "
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Denied papers</b></font>
		</legend>
		<table>
		<tr><td>There are no papers you have denied.</td></tr>
		</table>
		</fieldset>";
	} else {
		echo "
		<form method='POST' action='index.php?m=reviewer&a=denied&s=remove'>
		<input type='hidden' name='userID' value='".$user."'>
		<table align='left' width='100%'><tr><td>
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Denied papers</b></font>
		</legend>
		Here you can see papers you have denied. This is a blocking list as you will not be asked to review a paper which is on this list.<br>
		You can remove some papers from your blockin list, so you may be asked to rate a paper:<br>
		<select size='1' name='paperlist'>
		";
		$line = 0;
		foreach ($paperlist as $paper)
		{
			if ($line==0) { echo "<option selected value='".$paper['id']."'>".$paper['text']."</option>"; }
			else { echo "<option value='".$paper['id']."'>".$paper['text']."</option>"; }
			$line++;
		}
		echo "
		</select>
		<p><input type='submit' value='remove' name='remove'></p>
		</form>";
	}
  include('templates/footer.tpl.php');
?>