<?
  include('templates/header.tpl.php');
  $input = d("errors");
  $paperlist = $TPL['paperlist'];
?>
<form method="POST" action="index.php?m=reviewer&a=request&s=choose">
  <p>
    <select size="1" name="paper">
    <? foreach ($paperlist as $paper)
    {
    	echo "<option value='".$paper['paperid']."'>".$paper['conference'].": ".$paper['paper']."</option>";
    }
    ?>
    </select>
  </p>
  <p><input type="submit" value="choose" name="B1"></p>
</form>
<?
  include('templates/footer.tpl.php');
?>