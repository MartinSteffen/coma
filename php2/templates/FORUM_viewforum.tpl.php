<?
	include("header.tpl.php");
	$msg = $TPL['msg'];
	$messagelist = $TPL['Messageliste'];

	parse_str($_SERVER['QUERY_STRING']);
	$query = 'm='.$m.'&a='.$a.'&forumID='.$forumID;

?>
<p><b>Messages</b></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?
  	if (isset($_GET['msgid']))
  	{
  		echo
  		"<tr><td>
			<table width='100%' border='0' cellspacing='0' cellpadding='0'>
			  <tr>
				<td width='20%'>Subject</td>
				<td>".$msg['subject']."</td>
			  </tr>
			  <tr>
				<td width='20%'>Sender</td>
				<td>".$msg['sender']."</td>
			  </tr>
			  <tr>
				<td width='20%'>In Reply To</td>
				<td>".$msg['reply_to']."</td>
			  </tr>
			  <tr>
				<td width='20%'>Sendtime</td>
				<td>".$msg['send_time']."</td>
			  </tr>
			  <tr>
				<td width='20%'>Text</td>
				<td>".$msg['text']."</td>
			  </tr>
			</table>
  		</td></tr>";
  	}
  ?>
  <tr>
  	<td><hr></td>
  </tr>
  <tr>
  	<td>
  		<? echo "<a href='".$_SERVER['PHP_SELF']."?".$query."&s=new'>New Message</a> ";
  		if (isset($_GET['msgid'])) 	{
  		   echo "<a href='".$_SERVER['PHP_SELF']."?".$query."&s=new&msgid=".$msg['id']."'>Reply</a>";
  		}
  		?>
  	</td>
  </tr>
  <tr>
  	<td><hr></td>
  </tr>
  <tr><td>
  	<?
  		foreach ($messagelist as $message)
  		{
  			for ($i=0; $i < $message['tiefe']; $i++) { echo "&nbsp;&nbsp;&nbsp;"; }

  			echo "<a href='".$_SERVER['PHP_SELF']."?".$query."&msgid=".$message['ID']."'>".$message['subject']."</a>&nbsp;(from ".$message['sender']." at ".$message['sendtime'].")<br>";
  		}
  	?>
  </td></tr>
</table>
<?
	include("footer.tpl.php");
?>