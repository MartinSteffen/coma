<? 
include("header.tpl.php");
$input = d('tasks');
?>

  <? 
  $isEmpty=true;
  foreach($input as $role)
  {
  	$tasksInRole = $role['tasks'];	
  	foreach($tasksInRole as $tasks)
	{ 
		$isEmpty = false;
	}
  }

  if ($isEmpty==true)
		{  ?>  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>		
	<td class="textBold">No tasks are waiting for you.</td>
  </tr>
</table>			
  <?    }
     foreach($input as $role)
  	{ 
		$tasksInRole = $role['tasks'];
		if (!count($tasksInRole)==0)
		{		
  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td class="textBold">List of all your tasks that are waiting for you as <? echo $role['role'] ?>.</td>	 	
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <? 	foreach($tasksInRole as $tasks) 
	    {
		 	foreach($tasks as $task)
		    { ?>
  <tr align="left" valign="top"> 
    <td class="textBold" width="151"><? echo $task['text'] ?></td>
    <td class="text" width="100%"> 
      <? echo $task['action'] ?>
      <br>
    </td>
  </tr>  
  		 <? } ?>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="151" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
  <?   }  ?>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
</table>
<?
    }}
include("footer.tpl.php");
?>
