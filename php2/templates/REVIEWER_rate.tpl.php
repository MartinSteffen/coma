<?
  include('templates/header.tpl.php');
  $input = d("errors");

?>
<form name="form1" method="post" action="index.php?m=reviewer&a=review&s=setrating">
<input type="hidden" name="userID" value="<? echo $user ?>">
<input type="hidden" name="paperID" value="<? echo $TPL['paper']['id'] ?>">
<input type="hidden" name="reviewreportID" value="<? echo $TPL['reviewreport']['id'] ?>">
<table>
<tr>
  <td with='25%'>Paper</td>
  <td with='75%' colspan='3'><? echo $TPL['paper']['title']." : klick-to-download" ?></td>
</tr>
<tr>
  <td with='25%'>criterion</td>
  <td with='25%'>description</td>
  <td with='25%'>grade</td>
  <td with='25%'>comment</td>
</tr>

<?
  $criterionlist = $TPL['criterionlist'];
  if (count($criterionlist)>0)
  {
	  foreach ($criterionlist as $criterion)
	  {
	    echo "
	      <tr>
	        <td width='25%'>".$criterion['name']."</td>
            <td width='25%'>".$criterion['text']."</td>
            <td width='25%'><select size='1' name='G".$criterion['name']."'>";
            for ($count = 0; $count < $criterion['max']; $count++)
            {
              if ($criterion['grade']==($count+1)) {
                echo "<option selected>".($count+1)."</option>";
              } else {
                echo "<option>".($count+1)."</option>";
              }
            }
        echo "
            </select></td>
          <td width='25%'><input type='text' name='K".$criterion['name']."' size='20' value='".$criterion['comment']."'></td>
         </tr>";
	  }
  }
?>
  <tr>
	<td width='25%'>Summary</td>
	<td width='75%' colspan="3">
	  <textarea rows="2" name="summary" cols="20"><? echo $TPL['reviewreport']['summary'] ?></textarea>
	</td>
 </tr>
  <tr>
	<td width='25%'>Remarks</td>
	<td width='75%' colspan="3">
	  <textarea rows="2" name="remarks" cols="20"><? echo $TPL['reviewreport']['remarks'] ?></textarea>
	</td>
 </tr>
  <tr>
	<td width='25%'>Confidential</td>
	<td width='75%' colspan="3">
	  <textarea rows="2" name="confidential" cols="20"><? echo $TPL['reviewreport']['confidential'] ?></textarea>
	</td>
 </tr>
</table>
<input type="submit" value="rate" name="rate">
</form>
<?
  include('templates/footer.tpl.php');
?>