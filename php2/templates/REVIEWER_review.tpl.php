<?
  include('templates/header.tpl.php');
  $input = d("errors");
  $paperlist = $TPL['paperlist'];

	if (count($paperlist)==0)
	{
		echo "
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Rate</b></font>
		</legend>
		<table>
		<tr><td>There are no papers you can rate.</td></tr>
		</table>
		</fieldset>";
	} else {
		echo "
		<form method='POST' action='index.php?m=reviewer&a=review&s=review'>
		<input type='hidden' name='userID' value='".$user."'>
		<table align='left' width='100%'><tr><td>
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Rate</b></font>
		</legend>
		&nbsp;<br>You can choose the paper you want to rate:<br>
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
		<p><input type='submit' value='rate' name='rate'></p>
		</form>";
	}
  include('templates/footer.tpl.php');
?>