<?
  include('templates/header.tpl.php');
  $input = d("errors");
  $paperlist = $TPL['paperlist'];

	if (count($paperlist)==0)
	{
		echo "
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Request</b></font>
		</legend>
		<table>
		<tr><td>There are no papers you can request.</td></tr>
		</table>
		</fieldset>";
	} else {
		echo "
		<form method='POST' action='index.php?m=reviewer&a=request&s=choose'>
		<table align='left' width='100%'><tr><td>
		<fieldset>
		<legend>
		<font class='textBold' size='+2'><b>Request</b></font>
		</legend>
		&nbsp;<br>You can choose the paper you want to review:<br>
		<select size='1' name='paper'>
		";
		foreach ($paperlist as $paper)
		{
			echo "<option value='".$paper['paperid']."'>".$paper['conference'].": ".$paper['paper']."</option>";
		}
		echo "
		</select>
		<p><input type='submit' value='choose' name='B1'></p>
		</form>";
	}
  include('templates/footer.tpl.php');
?>