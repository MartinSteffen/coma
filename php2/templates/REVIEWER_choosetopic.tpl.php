<?
  include('templates/header.tpl.php');
  $input = d("errors");
  $topiclist = $TPL['topiclist'];
?>
<form method="POST" action="index.php?m=reviewer&a=topic&s=choose">
    <?
    $conf = NULL;
    foreach ($topiclist as $topic)
    {
    	if ($topic['conference']!=$conf)
    	{
    		$conf=$topic['conference'];
    		echo "<b>".$conf."</b><br>";
    	}
    	echo "<input type='checkbox' name='C".$topic['conferenceid']."T".$topic['topicid']."' value='1' ".$topic['value'].">".$topic['topicname']."<br>";
    }
    ?>
  <p><input type="submit" value="set" name="B1"></p>
</form>
<?
  include('templates/footer.tpl.php');
?>