<?
  include('templates/header.tpl.php');
  $input = d("errors");

?>
<form name="form1" method="post" action="index.php?m=reviewer&a=review&s=review">
<input type="hidden" name="userID" value="<? echo $user ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Please choose the paper to rate :</td>
  </tr>
</table>
<br>
<select size="1" name="paperlist">
<?
  $line = 0;
  $paperlist = $TPL['paperlist'];
  if (count($paperlist)>0)
  {
	  foreach ($paperlist as $paper)
	  {
	    if ($line==0) { echo "<option selected value='".$paper['id']."'>".$paper['text']."</option>"; }
	    else { echo "<option value='".$paper['id']."'>".$paper['text']."</option>"; }
	    $line++;
	  }
  }
?>
</select>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  </tr>
</table>
<br>
<input type="submit" value="rate" name="rate">
</form>
<?
  include('templates/footer.tpl.php');
?>